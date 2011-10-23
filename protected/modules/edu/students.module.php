<?php
/*
	Модуль редактирования студентов
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');


//
// ВЫВОД
//
Template::top();
?>
Студенты
<?= Template::bottom() ?>