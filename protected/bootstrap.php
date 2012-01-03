<?php
// Конфиг
require 'config.php';
include 'lib/misc.functions.php';

// автозагрузка классов по требованию
function __autoload( $name ){
	$path = 'class/'. str_replace('\\', '/', $name) .'.class.php';
	if ( ! include($path) )
		die( "Class '$name' not found!" );
}
