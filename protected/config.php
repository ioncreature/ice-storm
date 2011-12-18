<?php
// DEVELOPER
if ( isset($_SERVER['ENV']) and ($_SERVER['ENV'] == "muchacho_home" or $_SERVER['ENV'] == "MUCHACHO") ){

	// Application title
	define( 'APP_TITLE', 'Ice Storm' );

	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"ice-storm" );

	// Memcached
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', 'http://ice/' );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	define( 'TEMPLATES_PATH', $_SERVER['DOCUMENT_ROOT'] .'/protected/templates/' );
	
	define( 'ADMIN_EMAIL', 'admin@ice' );
	
	// Путь до файла лога
	error_reporting( E_ALL );
	ini_set( "display_errors", false );
}

// PRODUCTION
else {
	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"videoboom" );
	define( 'DB_CHARSET',	true );
	
	// Memcached
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', "http://viboom/" );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	
	define( 'ADMIN_EMAIL', 'admin@icestorm.ru' );
	
	error_reporting(E_ALL);

	ini_set("display_errors", false);
}



// КОНФИГУРАЦИЯ PHP-EXTENSIONS
// MB_STRING
mb_internal_encoding("UTF-8");
?>