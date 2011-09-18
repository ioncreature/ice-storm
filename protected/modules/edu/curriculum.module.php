<?php
/*
	Модуль редактирования учебных тем внутри курса
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

$c_terms = $db->query("
	SELECT * FROM 
");

//
// ВЫВОД
//
// Template::add_js( '/js/jquery.hotkeys.js' );
// Template::add_js( '/js/ui/jquery-ui.js' );
// Template::add_css( '/js/ui/jquery-ui.css' );
Template::top();
?>
<h2>Расписание курсов учебного плана "<?= htmlspecialchars($curriculum['name']) ?>"</h2>


<!-- TEMPLATES -->

<script type="text/html" id="t_term">
	<ul class="course_stages_list"> 
	{{#stages}}
	<li stageid="{{id}}">
		<h3>{{stage_name}} {{order}} семестр</h3>
		<button class="add_theme" stageid="{{id}}" courseid="{{course_id}}">Добавить учебную тему</button>
		<ol class="course_stage" stageid="{{id}}">
		{{#themes}}
			{{>t_theme}}
		{{/themes}}
		</ol>
	</li>
	{{/stages}}
	</ul>
</script>

<?= Template::bottom() ?>