<?php
// Конфиг
include 'protected/config.inc.php';
include 'protected/lib/misc.functions.php';	

//автозагрузка классов по требованию
function __autoload( $name ){
	$inc = include 'protected/class/'. $name .'.class.php';
	if ( !$inc )
		die( "Class '$name' not found!");
}

try {
	// проверка авторизации
	$a = Auth::getInstance();
	$db = Fabric::get( 'db' );
	$acl = Auth::$acl;
	
	// обрабортка запроса
	$r = RequestParser::getInstance();
	define( "APP_HASH", $r->getHash() );	
	

	// -------
	//  ВЫВОД
	// -------
	include "protected/templates/global.tpl.php";
	switch ( $r->get(0) ){
		case "logout":
			$a->logout();
			header( 'Location: '. WEBURL );
			die;
			
		case 'test':
			include "protected/modules/test.module.php";
			break;
		
		// По умолчанию - главная страница
		case "index":
		default:
			include "protected/modules/index.module.php";
			break;
	}
}

//	------------
//	Отлов ошибок
//	------------
catch ( CacheException $e ){
	echo "Ошибка кеширования: ". $e->getMessage();
}
catch ( SQLException $e ){
	echo "Ошибка БД: ". $e->getMessage();
}
catch ( Exception $e ){
	echo "Неизвестная ошибка: ". $e->getMessage();
}

?>