<?php
// DEVELOPER
if ( isset($_SERVER['ENV']) and ($_SERVER['ENV'] == "muchacho_home" or $_SERVER['ENV'] == "MUCHACHO") ){
	// Application title
	define( 'APP_TITLE', 'Ice Storm' );

	define( 'APP_CHARSET', 'UTF-8' );

	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"ice-storm" );

	// Memcached
	define( "MMC_ENGINE", "Memcache" );
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', 'http://ice/' );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	define( 'PROTECTED_PATH', DOCUMENT_ROOT .'protected/' );
	define( 'TEMPLATES_PATH', PROTECTED_PATH .'templates/' );

	define( 'ADMIN_EMAIL', 'admin@ice' );
	
	define( 'IS_DEBUG', true );
	error_reporting( E_ALL );
	ini_set( "display_errors", true );
}

// PRODUCTION
else {
	// Application title
	define( 'APP_TITLE', 'Ice Storm' );

	define( 'APP_CHARSET', 'UTF-8' );

	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"dfhbfnbd" );
	define( 'DB_NAME',		"ice-storm" );

	// Memcached
	define( "MMC_ENGINE", "Memcached" );
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );

	// Paths
	define( 'WEBURL', "http://spvi.vv/ice-storm/" );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/ice-storm/' );
	define( 'PROTECTED_PATH', DOCUMENT_ROOT .'protected/' );
	define( 'TEMPLATES_PATH', PROTECTED_PATH .'templates/' );
	
	define( 'ADMIN_EMAIL', 'admin@spvi.vv' );


	define( 'IS_DEBUG', true );
	error_reporting( E_ALL );
	ini_set( "display_errors", true );
}



// КОНФИГУРАЦИЯ PHP-EXTENSIONS
// MB_STRING
mb_internal_encoding( APP_CHARSET );
?>