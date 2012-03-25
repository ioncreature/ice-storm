<?php
// class autoloading
function __autoload( $name ){
	$path = 'class/'. str_replace('\\', '/', $name) .'.class.php';
	if ( ! include($path) )
		die( "Class '$name' not found!" );
}


// Config
if ( isset($_SERVER['ENV']) and ($_SERVER['ENV'] == "muchacho_home" or $_SERVER['ENV'] == "MUCHACHO") )
	// dev
	$cfg = include 'config/dev.conf.php';
else
	// prod
	$cfg = include 'config/production.conf.php';
Ice::save_config( $cfg );


// Const
define( 'WEBURL', Ice::config('weburl') );
define( 'DOCUMENT_ROOT', Ice::config('document_root') );
define( 'PROTECTED_PATH', Ice::config('protected_path') );
define( 'TEMPLATES_PATH', PROTECTED_PATH .'templates/' );
define( 'APP_CHARSET', 'UTF-8' );


// php settings
error_reporting( Ice::config('is_debug') ? E_ALL : 0 );
ini_set( "display_errors", Ice::config('is_debug') );
mb_internal_encoding( APP_CHARSET ); // MB_STRING


// additional includes
include 'lib/misc.functions.php';