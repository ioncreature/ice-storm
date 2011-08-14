<?php
// DEVELOPER - ALEX MARENIN HOME
if ( isset($_SERVER['ENV']) and $_SERVER['ENV'] == "muchacho_home" ){

	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"ice-storm" );
	define( 'DB_CHARSET',	true );
	
	// Mongo
	define( 'MONGO_HOST', "localhost" );
	define( 'MONGO_PORT', 27017 );
	define( 'MONGO_USER', "" );
	define( 'MONGO_PASSWORD', "" );
	define( 'MONGO_NAME', "ice-storm" );
	
	// Memcached
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', 'http://ice/' );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	
	define( 'ADMIN_EMAIL', 'admin@icestorm.ru' );
	
	// Путь до файла лога
	define( 'PATH_TO_EXCEPTION_LOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/protected/exception.log' );
	error_reporting(E_ALL);
}


// DEVELOPER - MARINAT
elseif ( isset($_SERVER['ENV']) and $_SERVER['ENV'] == "marinat" ){
	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"ice-storm" );
	define( 'DB_CHARSET',	true );
	
	// Mongo
	define( 'MONGO_HOST', "localhost" );
	define( 'MONGO_PORT', 27017 );
	define( 'MONGO_USER', "" );
	define( 'MONGO_PASSWORD', "" );
	define( 'MONGO_NAME', "ice-storm" );
	
	// Memcached
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', 'http://ice/' );
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	
	define( 'ADMIN_EMAIL', 'admin@icestorm.ru' );
	
	// Путь до файла лога
	define( 'PATH_TO_EXCEPTION_LOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/protected/exception.log' );
	error_reporting(E_ALL);
}


//PRODUCTION
else {
	// Database
	define( 'DB_HOST',		"localhost" );
	define( 'DB_USER',		"root" );
	define( 'DB_PASSWORD',	"root" );
	define( 'DB_NAME',		"videoboom" );
	define( 'DB_CHARSET',	true );
	
	// Mongo
	define( 'MONGO_HOST', "localhost" );
	define( 'MONGO_PORT', 27017 );
	define( 'MONGO_USER', "" );
	define( 'MONGO_PASSWORD', "" );
	define( 'MONGO_NAME', "videoboom" );
	
	// Memcached
	define( "MMC_HOST", "localhost" );
	define( "MMC_PORT", "11211" );
	
	// Paths
	define( 'WEBURL', "http://viboom/");
	define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/' );
	
	define( 'ADMIN_EMAIL', 'admin@icestorm.ru' );
	
	// Путь до файла лога
	define( 'PATH_TO_EXCEPTION_LOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/protected/exception.log' );
	error_reporting(E_ALL);
	
	error_reporting(E_ALL);
	ini_set("display_errors", false);
}



// КОНФИГУРАЦИЯ PHP-EXTENSIONS
// MB_STRING
mb_internal_encoding("UTF-8");
?>