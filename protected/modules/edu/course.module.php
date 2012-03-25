<?php
/*
	Модуль редактирования учебных тем внутри курса
	Marenin Alex
	September 2011
*/

$r = \Request\Parser::get_instance();
$db = \Db\Factory::get('db');

// приближаем к боевым условиям
// usleep( 750*1000 );

// идентификатор учебного курса 'edu/themes/::course_id'
if ( !$r->is_int(2) )
	redirect( WEBURL );
$cid = $r->to_int(2);
$course = $db->fetch_query("SELECT * FROM edu_courses WHERE id = '$cid' LIMIT 1");
if ( !$course )
	redirect( WEBURL );
	

// запрос всех семестров и тем
if ( $r->equal('edu/course/::int/get') ){

	$course_stages = $db->query("
		SELECT 
			edu_course_terms.*,
			edu_courses.hours as course_hours,
			edu_courses.name, edu_courses.shortname
		FROM
			edu_course_terms
			LEFT JOIN edu_courses ON edu_course_terms.course_id = edu_courses.id
		WHERE
			edu_course_terms.course_id = '$cid'
		ORDER BY edu_course_terms.`order`
	");
	foreach ( $course_stages as $key => $cs ){
		$themes = $db->query("
			SELECT * 
			FROM edu_course_themes
			WHERE
				course_id = '$cid' and
				term_id = '{$cs['id']}'
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
	isset($r->name, $r->hours, $r->term_id, $r->course_id) ){
	
	$hours = (int) $r->hours;
	$sid = (int) $r->term_id;
	$cid = (int) $r->course_id;
	
	$db->start();
	$t_last = $db->fetch_query("
		SELECT max(`order`) as o
		FROM edu_course_themes 
		WHERE
			term_id = '$sid' and
			course_id = '$cid'		
	");
	$order = $t_last ? intval($t_last['o']) + 1 : 1;
	$tid = $db->insert( 'edu_course_themes', array(
		'name' => mb_substr( $r->name, 0, 300 ),
		'hours' => $hours,
		'course_id' => $cid,
		'term_id' => $sid,
		'order' => $order
	));
	$db->commit();
	
	die( json_encode( array( 
		'status' => true,
		'id' => $tid,
		'term_id' => $sid,
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


// сортировка учебных тем
elseif ( $r->equal('edu/course/::int/move') and isset($r->order, $r->theme_id, $r->course_id, $r->term_id ) ){
	$tid = (int) $r->theme_id;
	$cid = (int) $r->course_id;
	$sid = (int) $r->term_id;
	$order = (int) $r->order;
	
	$db->start();
	$theme = $db->fetch_query("
		SELECT *
		FROM edu_course_themes
		WHERE id = '$tid'
		LIMIT 1
	");
	if ( $theme ){
		$old_order = (int) $theme['order'];
		if ( $old_order === $order ){
			$db->commit();
			die( json_encode( array( 'status' => true )));
		}
		elseif ( $old_order > $order ){
			$db->query("
				UPDATE edu_course_themes
				SET
					`order` = `order` + 1
				WHERE
					term_id = '$sid' and
					`order` >= '$order' and
					`order` <= '$old_order' 
			");
			$db->query("
				UPDATE edu_course_themes
				SET `order` = '$order'
				WHERE
					id = '$tid'
				LIMIT 1
			");
			$db->commit();
			die( json_encode( array( 'status' => true )));
		}
		elseif ( $old_order < $order ){
			$db->query("
				UPDATE edu_course_themes
				SET
					`order` = `order` - 1
				WHERE
					term_id = '$sid' and
					`order` >= '$old_order' and
					`order` <= '$order' 
			");
			$db->query("
				UPDATE edu_course_themes
				SET `order` = '$order'
				WHERE
					id = '$tid'
				LIMIT 1
			");
			$db->commit();
			die( json_encode( array( 'status' => true )));
		}
	}
	else {
		$db->rollback();
		die( json_encode( array( 'status' => false )));
	}
}


//
// ВЫВОД
//
Template::add_js(  WEBURL .'js/jquery.hotkeys.js' );
Template::add_js(  WEBURL .'js/ui/jquery-ui.js' );
Template::add_css( WEBURL .'js/ui/jquery-ui.css' );
Template::top();
?>
<h2>Темы учебного курса "<?= htmlspecialchars($course['name']) ?>"</h2>


<!-- СПИСОК СЕМЕСТРОВ В КУРСЕ -->
<div id="course_stages">
<span class="loader"><div></div></span>
</div>


<script type="text/javascript">
$( document ).ready( function(){
	
	// начальная загрузка списков тем
	var init_terms = function(){
		$.ajax({
			url: '<?= WEBURL ."edu/course/$cid/get" ?>',
			type: 'POST',
			dataType: 'json',
				beforeSend: function(){
				$( 'span.loader', $('#course_stages') ).show();
			},
			success: function( data ){
				if ( data.status ){
					$( 'span.loader', $('#course_stages') ).hide();
					var stages = $(ich.t_term(data)); 
					$( '#course_stages' ).append( stages );
					var themes_list = $( 'ol.course_stage', stages );
					themes_list.sortable({
						start: function(){
							$( 'li.theme' ).show();
							$( 'li.edit_form' ).remove();
						},
						stop: function( event, ui ){
							themes_list.sortable( "option", "disabled", true );
							var elem = ui.item[0];
							var data = {
								order: reorder_list( elem ),
								theme_id: $(elem).attr( 'themeid' ),
								course_id: $(elem).attr( 'courseid' ),
								term_id: $(elem).attr( 'stageid' )
							};
							
							$( 'span.loader', elem ).show();
							$.ajax({
								url: '<?= WEBURL ."edu/course/$cid/move" ?>',
								data: data,
								type: 'POST',
								dataType: 'json',
								success: function( data ){
									if ( data.status ){
										console.log('moved succesfully');
									}
									else {
									}
								},
								complete: function(){
									themes_list.sortable( "option", "disabled", false );
									$( 'span.loader', elem ).hide();
								}
							});
						}
					});
					$( 'button.add_theme' ).click( add_theme );
					$( 'li.theme' ).dblclick( edit_theme );
				}
			}
		});
	}
	init_terms();
	
	
	// перебивает значения аттрибута order у всех siblings и 
	// возвращает значение аттрибута указанного элемента
	var reorder_list = function( elem ){
		var parent = elem.parentNode;
		$(parent).children().each( function( i ){
			$(this).attr( 'order', i+1 );
		});
		return $(elem).attr('order');
	}
	
	
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
		$('input[name=name]', form).focus();
		var remove_form = function(){
			$(form).remove();
			$(self).show();
		};
		$( 'input[type=button]', form ).click( remove_form );
		$('input, select', form).bind( 'keydown', 'esc', remove_form );
		
		$('form', form).ajaxForm({
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function( args, fe ){
				var name = $.trim( $( 'input[name=name]', fe ).val() );
				var hours = $.trim( $( 'input[name=hours]', fe ).val() );
				if ( name.length < 4 )
					return false;
				if ( Number(hours) < 0 || Number(hours) > 50 )
					return false;
				if ( name === param.name && hours === param.hours ){
					remove_form();
					return false;
				}
				$( 'span.loader', fe ).show();
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
					$( 'span.loader', form ).hide();
					remove_form();
					alert( 'Произошла ошибка' );
				}
			}
		});
	}
	
	
	// Создание темы
	var add_theme = function(){	
		var self = this;
		
		// предотвращаем повторное создание формы
		var parent = this.parentNode;
		var parent = $( 'ol.course_stage', this.parentNode ).get(0);
		if ( $('.add_theme_form', parent).length > 0 ){
			$('.add_theme_form input[name="name"]', parent).focus();
			return false;
		}
		
		var sid = $(this).attr( 'stageid' );
		var cid = $(this).attr( 'courseid' );
		console.log(sid);
		
		var form = $(ich.t_form_theme({
			'term_id': sid,
			'course_id': cid
		}));
		$(this).after( form );
		$(this).hide();
		$( 'input[name=name]', form ).focus();
		
		var remove_form = function(){ 
			$(form).remove(); 
			$(self).show();
			$(self).focus();
		};
		$( 'input[type=button]', form ).click( remove_form );
		$( 'input, select', form ).bind( 'keydown', 'esc', remove_form );
		
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
				$( 'span.loader', form ).show();
			},
			success: function( data ){
				console.log( data );
				
				if ( data.status ){
					var theme = $( ich.t_theme(data) );
					$(parent).append( theme );
					$(theme).dblclick( edit_theme );
					remove_form();
					$(self).focus();
				}
				else {
					alert('Произошла ошибка');
					$( 'span.loader', form ).hide();
				}
			}
		});
	}
});
</script>


<!-- TEMPLATES -->
<script type="text/html" id="t_form_theme">
	<form class="add_theme_form" method="POST" action="<?= WEBURL ."edu/course/$cid/add_theme" ?>">
		<input type="text" placeholder="название темы" name="name" value="" class="common" />
		<input type="text" placeholder="кол-во часов" name="hours" value="" class="common" />
		<input type="hidden" name="term_id" value="{{term_id}}" />
		<input type="hidden" name="course_id" value="{{course_id}}" />
		<input type="submit" value="сохранить" class="common" />
		<input type="button" value="отмена" class="common" />
		<span class="loader"><div></div></span>
	</form>
</script>

<script type="text/html" id="t_term">
	<ul class="course_stages_list"> 
	{{#stages}}
	<li stageid="{{id}}">
		<h3>{{stage_name}} {{order}} семестр</h3>
		<button class="add_theme common" stageid="{{id}}" courseid="{{course_id}}" >Добавить учебную тему</button>
		<ol class="course_stage" stageid="{{id}}">
		{{#themes}}
			{{>t_theme}}
		{{/themes}}
		</ol>
	</li>
	{{/stages}}
	</ul>
</script>

<script type="text/html" id="t_theme" class="partial">
	<li class="theme" themeid="{{id}}" courseid="{{course_id}}" stageid="{{term_id}}" themename="{{name}}" hours="{{hours}}" order="{{order}}">
		<span class="name">{{name}}</span>
		<span class="hours">{{hours}} ч.</span>
		<span class="loader"><div></div></span>
	</li>
</script>

<script type="text/html" id="t_form_edit_theme">
	<li class="edit_form">
	<form class="edit_theme_form" method="POST" action="<?= WEBURL ."edu/course/$cid/edit_theme" ?>">
		<input type="text" placeholder="название темы" name="name" value="{{name}}" style="width: 300px;" class="common" />
		<input type="text" placeholder="кол-во часов" name="hours" value="{{hours}}" class="common" />
		<input type="hidden" name="theme_id" value="{{theme_id}}" />
		<input type="submit" value="редактировать" class="common" />
		<input type="button" value="отмена" class="common" />
		<span class="loader"><div></div></span>
	</form>
	</li>
</script>

<?= Template::bottom() ?>