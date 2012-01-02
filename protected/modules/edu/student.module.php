<?php
/*
	Модуль редактирования студента
	Marenin Alex
	October
	2011
*/

$r = \Request\Parser::get_instance();
$db = Fabric::get('db');

$sid = $r->to_int(2);
if ( !$sid )
	redirect( WEBURL );

$student = $db->fetch_query("
	SELECT 
		edu_students.*,
		org_humans.*
	FROM 
		edu_students
		LEFT JOIN org_humans ON org_humans.id = edu_students.human_id
	WHERE
		edu_students.id = '$sid'
	LIMIT 1  
");
if ( !$student )
	redirect( WEBURL );


//
// ВЫВОД
//
Template::top();
?>
<h2>Студент <?= htmlspecialchars($student['last_name'] .' '. $student['first_name'] .' '. $student['middle_name'] )?></h2>


<?= Template::bottom() ?>