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
	$a = Auth::get_instance();
	$db = Fabric::get( 'db' );
	$acl = Auth::$acl;
	
	// обрабортка запроса
	$r = RequestParser::get_instance();
	define( "APP_HASH", $r->get_hash() );	
	

	// -------
	//  ВЫВОД
	// -------
	include "protected/templates/global.tpl.php";
	switch ( $r->get(0) ){
		case "acl":
			switch ($r->get(1)){
				case "groups":
					include "protected/modules/acl/groups.module.php";
					break;	
			}
			break;
		
		case "logout":
			$a->logout();
			redirect( WEBURL );
		
		case "register":
			include "protected/modules/register.module.php";
			break;
		
		// тестовая страница
		case 'test':
			include "protected/modules/test.module.php";
			break;
		
		// по умолчанию - главная страница
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