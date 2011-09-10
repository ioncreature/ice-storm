<?php
/*
	Модуль редактирования учебных тем внутри курса
	Marenin Alex
	September 2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

// приближаем к боевым условиям
usleep( 750*1000 );

// идентификатор учебного курса 'edu/themes/::course_id'
if ( !$r->is_int(2) )
	redirect( WEBURL );
$cid = $r->to_int(2);

$course = $db->fetch_query("SELECT * FROM edu_courses WHERE id = '$cid' LIMIT 1");

// запрос всех семестров и тем
if ( $r->equal('edu/course/::int/get') ){

	$course_stages = $db->query("
		SELECT 
			edu_course_stages.*,
			edu_courses.hours as course_hours,
			edu_courses.name, edu_courses.shortname
		FROM
			edu_course_stages
			LEFT JOIN edu_courses ON edu_course_stages.course_id = edu_courses.id
		WHERE
			edu_course_stages.course_id = '$cid'
		ORDER BY edu_course_stages.`order`
	");
	foreach ( $course_stages as $key => $cs ){
		$themes = $db->query("
			SELECT * 
			FROM edu_course_themes
			WHERE
				course_id = '$cid' and
				stage_id = '{$cs['id']}'
			ORDER BY `order`
		");
		$course_stages[$key]['themes'] = $themes ? $themes : array();
	}
	die( json_encode( array(
		'status' => true,
		'stages' => $course_stages
	)));

}


// создание новой темы
elseif ( $r->equal('edu/course/::int/add_theme') and 
	isset($r->name, $r->hours, $r->stage_id, $r->course_id) ){
	
	$hours = (int) $r->hours;
	$sid = (int) $r->stage_id;
	$cid = (int) $r->course_id;
	
	$db->start();
	$t_last = $db->fetch_query("
		SELECT max(`order`) as o
		FROM edu_course_themes 
		WHERE
			stage_id = '$sid' and
			course_id = '$cid'		
	");
	$order = $t_last ? intval($t_last['o']) + 1 : 1;
	$tid = $db->insert( 'edu_course_themes', array(
		'name' => mb_substr( $r->name, 0, 300 ),
		'hours' => $hours,
		'course_id' => $cid,
		'stage_id' => $sid,
		'order' => $order
	));
	$db->commit();
	
	die( json_encode( array( 
		'status' => true,
		'stage_id' => $sid,
		'course_id' => $cid,
		'name' => mb_substr( $r->name, 0, 300 ),
		'hours' => $hours,
		'order' => $order
	)));
}


// редактирование темы
elseif ( $r->equal('edu/course/::int/edit_theme') and isset($r->name, $r->hours, $r->theme_id) ){
	$name = $db->safe( mb_substr($r->name, 0, 300) );
	$id = (int) $r->theme_id;
	$hours = (int) $r->hours;
	
	if ( !$id or !$name or !$hours )
		die( json_encode( array( 'status' => false )));
	
	$db->start();
	$theme = $db->fetch_query("SELECT * FROM edu_course_themes WHERE id = '$id'");
	if ( !$theme ){
		$db->rollback();
		die( json_encode( array( 'status' => false )));
	}	
	$db->query("
		UPDATE edu_course_themes
		SET
			hours = '$hours',
			name = '$name'
		WHERE
			id = '$id'
		LIMIT 1
	");
	$db->commit();
	
	die( json_encode( array( 
		'status' => true,
		'order' => $theme['order'],
		'id' => $id,
		'name' => $name,
		'hours' => $hours
	)));
}


//
// ВЫВОД
//
top();
?>
<h2>Темы учебного курса "<?= htmlspecialchars($course['name']) ?>"</h2>


<!-- СПИСОК СЕМЕСТРОВ В КУРСЕ -->
<ul id="course_stages">
<div class="loader"></div>
</ul>


<script type="text/javascript">
$( document ).ready( function(){
	
	// начальная загрузка списков тем
	var init_stages = function(){
		$.ajax({
			url: '<?= WEBURL ."edu/course/$cid/get" ?>',
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$( 'div.loader', $('#course_stages') ).show();
			},
			success: function( data ){
				if ( data.status ){
					$( 'div.loader', $('#course_stages') ).hide();
					var stages = $(ich.t_stage(data)); 
					$( '#course_stages' ).append( stages );
					$( 'button.add_theme' ).click( add_theme );
					$( 'li.theme' ).dblclick( edit_theme );
				}
			}
		});
	}
	init_stages();
	
	
	// Редактирование темы
	var edit_theme = function(){
		var self = this;
		
		var param = {
			'theme_id': $(this).attr('themeid'),
			'name': $(this).attr('themename'),
			'hours': $(this).attr('hours')
		};
		var form = $( ich.t_form_edit_theme(param) );
		$(this).hide();
		$(this).after( form );
		var remove_form = function(){
			$(form).remove();
			$(self).show();
		};
		$( 'input[type=button]', form ).click( remove_form );
		
		$('form', form).ajaxForm({
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function( args, fe ){
				var name = $( 'input[name=name]', fe ).val();
				var hours = $( 'input[name=hours]', fe ).val();
				if ( name.length < 4 || name === param.name )
					return;
				if ( Number(hours) < 0 || Number(hours) > 50 || $.trim(hours) === param.hours )
					return;
			},
			success: function( data ){
				if ( data.status ){
					var theme = $( ich.t_theme(data) );
					$(form).remove();
					$(self).after( theme );
					$(theme).dblclick( edit_theme );
					$(self).remove();
				}
				else {
					remove_form();
					alert( 'Произошла ошибка' );
				}
			}
		});
	}
	
	
	// Создание темы
	var add_theme = function(){	
		// предотвращаем повторное создание формы
		var parent = this.parentNode;
		if ( $('.add_theme_form', parent).length > 0 ){
			$('.add_theme_form input[name="name"]', parent).focus();
			return false;
		}
		
		var sid = $(this).attr( 'stageid' );
		var cid = $(this).attr( 'courseid' );
		console.log(sid);
		
		var form = $(ich.t_form_theme({
			'stage_id': sid,
			'course_id': cid
		}));
		$(this).after( form );
		$( 'input[type=button]', form ).click( function(){
			$(form).remove();
		});
		
		$( form ).ajaxForm({
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function(){
				// валидация
				var name = $('input[name=name]', form ).val();
				var hours = $('input[name=hours]', form ).val();
				
				if ( name.length < 4 )
					return false;
				if ( Number(hours) < 1 && Number(hours) > 50 )
					return false;
				$( 'div.loader', form ).css('display', 'inline-block');
			},
			success: function( data ){
				console.log( data );
				
				if ( data.status ){
					$(parent).append( ich.t_theme(data) );
					$(form).remove();
				}
				else {
					alert('Произошла ошибка');
					$( 'div.loader', form ).hide();
				}
			}
		});
	}
});
</script>


<!-- TEMPLATES -->
<script type="text/html" id="t_form_theme">
	<form class="add_theme_form" method="POST" action="<?= WEBURL ."edu/course/$cid/add_theme" ?>">
		<input type="text" placeholder="название темы" name="name" value="" />
		<input type="text" placeholder="кол-во часов" name="hours" value="" />
		<input type="hidden" name="stage_id" value="{{stage_id}}" />
		<input type="hidden" name="course_id" value="{{course_id}}" />
		<input type="submit" value="сохранить"/>
		<input type="button" value="отмена" />
		<div class="loader"></div>
	</form>
</script>

<script type="text/html" id="t_stage">
	<ul> 
	{{#stages}}
	<li stageid="{{id}}">
		<h3>{{stage_name}} {{order}} семестр</h3>
		<ul class="course_stage" stageid="{{id}}">
		<button class="add_theme" stageid="{{id}}" courseid="{{course_id}}">Добавить учебную тему</button>
		{{#themes}}
			{{>t_theme}}
		{{/themes}}
		</ul>
	</li>
	{{/stages}}
	</ul>
</script>

<script type="text/html" id="t_theme" class="partial">
	<li class="theme" themeid="{{id}}" courseid="{{course_id}}" stageid="{{stage_id}}" themename="{{name}}" hours="{{hours}}">
		<span class="order">{{order}}</span>
		<span class="name">{{name}}</span>
		<span class="hours">{{hours}} ч.</span>
	</li>
</script>

<script type="text/html" id="t_form_edit_theme">
	<li class="edit_form">
	<form class="edit_theme_form" method="POST" action="<?= WEBURL ."edu/course/$cid/edit_theme" ?>">
		<input type="text" placeholder="название темы" name="name" value="{{name}}" />
		<input type="text" placeholder="кол-во часов" name="hours" value="{{hours}}" />
		<input type="hidden" name="theme_id" value="{{theme_id}}" />
		<input type="submit" value="редактировать"/>
		<input type="button" value="отмена" />
		<div class="loader"></div>
	</form>
	</li>
</script>

<?= bottom() ?>