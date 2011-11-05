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


// Начальная загрузка 
if ( $r->equal('edu/curriculum/::int/get/stages') ){
	
	// Список семестров в учебном плане
	$terms = $db->query("
		SELECT *
		FROM edu_curriculum_terms
		WHERE curriculum_id = '$cid'
		ORDER BY `order`
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
		WHERE
			edu_courses.id <> ALL ( 
				SELECT course_id 
				FROM edu_curriculum_courses
				WHERE
					curriculum_id = '$cid'
			)
		GROUP BY edu_courses.id
	");
	
	// список семестров
	$courses_out = array();
	foreach ( $courses as $c ){
		$cterms = $db->query("
			SELECT id, `order`
			FROM edu_course_terms
			WHERE course_id = {$c['id']}
			ORDER BY `order`
		");
		$c['terms'] = $cterms;
		$courses_out[] = $c;
	}
	
	die( json_encode( array(
		'status'	=> true,
		'terms'		=> $terms,
		'courses'	=> $courses_out,
		'db_queries'=> $db->get_query_count()
	)));
}


// Сохранение курса в плане
if ( $r->equal("edu/curriculum/$cid/add/course") and
	isset($r->course_term_id, $r->course_id, $r->term_id)
){
	try {
		// валидация
		$course_term_id = (int) $r->course_term_id;
		$course_id = (int) $r->course_id;
		$term_id = (int) $r->term_id;
		
		$db->start();
		$course_t = $db->fetch_query("
			SELECT * 
			FROM edu_course_terms 
			WHERE
				course_id = '$course_id' AND
				id = '$course_term_id'
			LIMIT 1
		");
		$ct = $db->fetch_query("
			SELECT * 
			FROM edu_curriculum_terms
			WHERE 
				id = '$term_id' AND
				curriculum_id = '$cid'
			LIMIT 1
		");
		
		// добавляем в базу
		if ( !$course_t or !$ct )
			throw new Exception('Incorrect input data');
	
		$available_course_terms = $db->query("
			SELECT id 
			FROM 
				edu_course_terms
				FORCE INDEX( course_id_index )
			WHERE
				course_id = '$course_id' AND
				id <> ALL (
					SELECT course_term_id
					FROM edu_curriculum_courses
					WHERE
						curriculum_id = '$cid' AND
						course_id = '$course_id'
				)
			ORDER BY `order`
		");
		$available_curr_terms = $db->query("
			SELECT id
			FROM edu_curriculum_terms
			WHERE
				curriculum_id = '$cid' AND
				id >= '$term_id'
			ORDER BY `order`
		");
		if ( count($available_course_terms) > count($available_curr_terms) )
			throw new Exception('No enougnt curriculum terms');
		
		$cterms = array();
		for ( $i = 0; $i < count($available_course_terms); $i++ ){
			$db->insert( 'edu_curriculum_courses', array(
				'curriculum_id' => $cid,
				'course_id' => $course_id,
				'curriculum_term_id' => $available_curr_terms[$i]['id'],
				'course_term_id' => $available_course_terms[$i]['id']
			));
			$cterms[] = $available_course_terms[$i]['id'];
		}
		
		$db->commit();
		die( json_encode( array(
			'status' => true,
			'course_terms' => json_encode( $cterms ),
			'course_id' => $course_id
		)));
	}
	
	catch( Exception $e ){
		$db->rollback();
		die( json_encode( array(
			'status' => false,
			'error' => $e->getMessage()
		)));
	}
	
}



//
// ВЫВОД
//
Template::add_js( '/js/jquery.hotkeys.js' );
Template::add_js( '/js/underscore.js' );
Template::add_js( '/js/ICanHaz.js' );
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
		var form = $( ich.t_form_add_course({ 
			courses: courses,
			term_id: $(button).attr('termid')
		}));
		$(button).after( form );
		var select_term = $( 'select[name=course_term_id]', form );
		var remove_form = function(){
			$(button).show();
			$(form).remove();
		}
		$( 'input[type=button]', form ).click( remove_form );
		$( 'select', form ).bind( 'keydown', 'esc', remove_form );
		var t_opt = '{{#terms}}<option value="{{id}}">{{order}} часть</option>{{/terms}}';
		
		var fill_opt = function(){
			var value = $(this).val();
			for ( var i = 0; i < courses.length; i++ )
				if ( Number(value) === Number(courses[i].id) ){
					$(select_term).html( Mustache.to_html(t_opt, courses[i]) );
					break;
				}
		}
		fill_opt.call( $('select[name=course_id]', form) );
		
		// обработчик select'a с курсами
		$( 'select[name=course_id]', form ).change( fill_opt );
		
		// запрос
		$( form ).ajaxForm({
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function(){
				$( 'span.loader', form ).show();
			},
			success: function( data ){
				if ( data.status ){
					var course_data;
					for ( var i = 0; i < courses.length; i++ )
						if ( Number(courses[i].id) === Number(data.course_id) ){
							course_data = courses[i];
							var nc = [];
							for ( var j = 0; j < courses.length; j++ )
								if ( i != j )
									nc.push(courses[j]);
							courses = nc;
							break;
						}
					
					// добавление курса
					var term = $( parent );
					for ( var i = 0; i < data.course_terms.length; i++ ){
						term.append( ich.t_course({
							course_id: course_data.id,
							course_term_id: data.course_terms[i],
							curriculum_term_id: term.attr( 'termid' ),
							name: course_data.name,
							order: i+1
						}));
						term = term.next();
					}
				}
			},
			complete: function(){
				$( 'span.loader', form ).hide();
				remove_form();
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
			<button class="add_course" termid="{{id}}">Добавить учебный курс</button>
			<ul class="courses">
			{{#courses}}
				{{>t_course}}
			{{/courses}}
			</ul>
		</li>
	{{/terms}}
	</ul>
</script>

<script type="text/html" id="t_course" class="partial">
	<li courseid="{{course_id}}" coursetermid="{{course_term_id}}" curriculumtermid="{{curriculum_term_id}}">
		<span class="name">{{name}},</span> <span>{{order}} часть</span>
	</li>
</script>


<script type="text/html" id="t_form_add_course">
	<form class="form_add_course" method="POST" action="<?= WEBURL ."edu/curriculum/$cid/add/course" ?>">
		Курс
		<select name="course_id">
			{{#courses}}<option value="{{id}}">{{name}}</option>{{/courses}}
		</select>
		Часть
		<select name="course_term_id"></select>
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