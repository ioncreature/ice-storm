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
	define( "MMC_ENGINE", "Memcache" );
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', 'http://ice/' );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	define( 'PROTECTED_PATH', DOCUMENT_ROOT .'protected/' );
	define( 'TEMPLATES_PATH', PROTECTED_PATH .'templates/' );

	define( 'ADMIN_EMAIL', 'admin@ice' );

	// View
	define( 'DEFAULT_VIEW', '\View\WebPage' );
	define( 'DEFAULT_LAYOUT', 'layout/base' );

	define( 'IS_DEBUG', true );
}

// PRODUCTION
else {
	// Application title
	define( 'APP_TITLE', 'Ice Storm' );

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

	// View
	define( 'DEFAULT_VIEW', '\View\WebPage' );
	define( 'DEFAULT_LAYOUT', 'layout/base' );

	define( 'IS_DEBUG', true );
}
?>