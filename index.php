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
	$acl = Auth::$acl;
	
	// обрабортка запроса
	$r = RequestParser::get_instance();
	define( "APP_HASH", $r->get_hash() );	
	
	// usleep(500*1000);

	// -------
	//  ВЫВОД
	// -------
	include "protected/templates/global.tpl.php";
	switch ( $r->get(0) ){
		
		// разграничение доступа
		case "acl":
			switch ($r->get(1)){
				case "groups":
					include "protected/modules/acl/groups.module.php";
					break;
				case "users":
					include "protected/modules/acl/users.module.php";
					break;
				case "usersingroups":
					include "protected/modules/acl/usersingroups.module.php";
					break;
				default:
					redirect( WEBURL );
			}
			break;
			
		// Кабинет пользователя
		case "user":
			if ( $a->is_logged() )
				switch ($r->get(1)){
					case "cabinet":
						include "protected/modules/user/cabinet.module.php";
						break;
					default:
						redirect( WEBURL );
				}
			else
				include "protected/modules/access_denied.module.php";
			break;
			
		
		// Учебный процесс
		case "edu":
			switch ($r->get(1)){
				case "curriculums":
					include "protected/modules/edu/curriculums.module.php";
					break;
				case "curriculum":
					include "protected/modules/edu/curriculum.module.php";
					break;
				case "courses":
					include "protected/modules/edu/courses.module.php";
					break;
				case "course":
					include "protected/modules/edu/course.module.php";
					break;
				case "groups":
					include "protected/modules/edu/groups_tree.module.php";
					break;
				case "groups_list":
					include "protected/modules/edu/groups_list.module.php";
					break;
				case "group":
					include "protected/modules/edu/group.module.php";
					break;
				case "students":
					include "protected/modules/edu/students.module.php";
					break;
				case "student":
					include "protected/modules/edu/student.module.php";
					break;
				default:
					redirect( WEBURL );
			}
			break;
		
		
		// Структура учреждения
		case "org":
			switch ($r->get(1)){
				case "departments":
					include "protected/modules/org/departments.module.php";
					break;
				default:
					redirect( WEBURL );
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
		
		// тестовая backbone страница
		case 'mvc':
			include "protected/modules/mvc.module.php";
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