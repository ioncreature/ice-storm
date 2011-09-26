<?php
/*
	Модуль редактирования учебных курсов внутри учебного плана
	Marenin Alex
	September 2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');


// идентификатор учебного плана 'edu/curriculum/::course_id'
if ( !$r->is_int(2) )
	redirect( WEBURL );
$cid = $r->to_int(2);
$curriculum = $db->fetch_query("SELECT * FROM edu_curriculums WHERE id = '$cid' LIMIT 1");
if ( !$curriculum )
	redirect( WEBURL );


if ( $r->equal('edu/curriculum/::int/get/stages') ){
	
	// Список семестров в учебном плане
	$terms = $db->query("
		SELECT
			edu_curriculum_terms.*
		FROM 
			edu_curriculum_terms
		WHERE
			edu_curriculum_terms.curriculum_id = '$cid'
		ORDER BY 
			edu_curriculum_terms.`order`
	");
	foreach ( $terms as $key => $t ){
		$courses = $db->query("
			SELECT 
				edu_curriculum_courses.*,
				edu_courses.name,
				edu_course_terms.term_name,
				edu_course_terms.order
			FROM 
				edu_curriculum_courses
				LEFT JOIN edu_courses ON
					edu_courses.id = edu_curriculum_courses.course_id
				LEFT JOIN edu_course_terms ON
					edu_course_terms.id = edu_curriculum_courses.course_term_id
			WHERE
				edu_curriculum_courses.curriculum_term_id = '{$t['id']}'
		");
		$terms[$key]['courses'] = $courses;
	}
	
	// список курсов
	$courses = $db->query("
		SELECT 
			edu_courses.*,
			GROUP_CONCAT( edu_course_terms.id SEPARATOR ',' ) as terms
		FROM 
			edu_courses
			LEFT JOIN edu_course_terms ON
				edu_course_terms.course_id = edu_courses.id
		GROUP BY edu_courses.id
	");
	$courses_out = array();
	foreach ( $courses as $c ){
		$c['terms'] = explode( ',', $c['terms'] );
		$courses_out[] = $c;
	}
	
	die( json_encode( array(
		'status'	=> true,
		'terms'		=> $terms,
		'courses'	=> $courses_out
	)));
}



//
// ВЫВОД
//
Template::add_js( '/js/jquery.hotkeys.js' );
Template::top();
?>
<h2>Расписание курсов учебного плана "<?= htmlspecialchars($curriculum['name']) ?>"</h2>

<!-- LIST OF CURRICULUM TERMS -->
<div id="curriculum_terms">
	<span class="loader" style="display: inline-block;"><div></div></span>
</div>


<script type="text/javascript">
	// список всех курсов и их семестров
	var courses;

	// начальная загрузка данных
	(function(){
		$.ajax({
			url: '<?= WEBURL ."edu/curriculum/$cid/get/stages" ?>',
			type: 'POST',
			dataType: 'json',
			success: function( data ){
				if ( !data.status ){
					console.log( 'Error occured!', data );
					return false;
				}
				courses = data.courses
				$( '#curriculum_terms' ).html( ich.t_main(data) );
				// вешаем обработчики
				$( 'button.add_course' ).click( show_add_course_form );
			}
		});
	})();
	
	var show_add_course_form = function(){
		var button = this;
		var parent = this.parentNode;
		
		$(button).hide();
		var form = $( ich.t_form_add_course({ courses: courses }) );
		$(button).after( form );
		var remove_form = function(){
			$(button).show();
			$(form).remove();
		}
		$( 'input[type=button]', form ).click( remove_form );
		$( 'select', form ).bind( 'keydown', 'esc', remove_form );
		
		// обработчик select'a с курсами
		$( 'select[name=course_id]', form ).change( function(){
			var value = $(this).val();
			var template = '{{#terms}}<option value="{{.}}">{{.}} часть</option>{{/terms}}';
			for ( var i = 0; i < courses.length; i++ ){
				if ( Number(value) === Number(courses[i].id) )
					$(this).html( Mustache.to_html(template, courses[i]) );
					break;
				}
			}
		})
		
		$( form ).ajaxForm({
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function(){
				$( 'span.loader', form ).show();
			},
			success: function( data ){
				
			},
			complete: function(){
				$( 'span.loader', form ).hide();
			}
		});
	}
	
	var add_course = function( data ){
		var self = this;
		
		$.ajax({
			url: '<?= WEBURL ."edu/curriculum/$cid/add/course" ?>',
			type: 'POST',
			data: {},
			dataType: 'json',
			success: function( data ){
				
			}
		});
	}
</script>


<!-- TEMPLATES -->
<script type="text/html" id="t_main">
	<ul class="course_stages_list">
	{{#terms}}
		<li termid="{{term_id}}">
			<h3>{{order}} семестр</h3>
			<button class="add_course" termid="{{term_id}}">Добавить учебный курс</button>
			<ul class="courses">
			{{#courses}}
				{{>t_courses}}
			{{/courses}}
			</ul>
		</li>
	{{/terms}}
	</ul>
</script>

<script type="text/html" id="t_form_add_course">
	<form class="form_add_course" method="POST" action="<?= WEBURL ."edu/curriculum/$cid/add/course" ?>">
		Курс
		<select name="course_id">
			{{#courses}}<option value="{{id}}">{{name}}</option>{{/courses}}
		</select>
		Часть<select name="course_term_id"></select>
		<input type="hidden" name="term_id" value="{{term_id}}" />
		<input type="submit" value="добавить" />
		<input type="button" value="отмена" />
		<span class="loader"><div></div></span>
	</form> 
</script>

<script type="text/html" id="t_term_courses" class="partial">
	<li coursetermid="{{term_id}}">hello!</li>
</script>

<?= Template::bottom() ?>