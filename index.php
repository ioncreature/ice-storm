<?php
require "protected/bootstrap.php";

try {
	\Ice::create_application( '\SpviApp' );

	// проверка авторизации
	$a = Auth::get_instance();
	$acl = Auth::$acl;
	
	// обработка запроса
	$r = new \Request\Parser();
	define( "APP_HASH", $r->get_hash() );	
	$response = new \Response\Http();

	// -------
	//  ВЫВОД
	// -------
	include "protected/templates/global.tpl.php";
	switch ( $r->get(0) ){
		
		// разграничение доступа
		case "acl":
			switch ( $r->get(1) ){
				case "groups":
					$response->send_controller( new \Controller\acl\Groups($r, 'acl/groups/') );
					die;
				case "users":
					$response->send_controller( new \Controller\acl\Users($r, 'acl/users/') );
					die;
				case "usersingroups":
					include "protected/modules/acl/usersingroups.module.php";
					break;
				default:
					redirect( WEBURL );
			}
			break;

		case "service":
			switch ( $r->get(1) ){
				case "department":
					$response->send_controller( new \Service\Departments($r, 'service/department/') );
					die;
				case "staff":
					$response->send_controller( new \Service\Staff($r, 'service/staff/') );
					die;
				case "students":
					$response->send_controller( new \Service\Students($r, 'service/students/') );
					die;
				default:
					redirect( WEBURL );
			}

		// Кабинет пользователя
		case "user":
			if ( $a->is_logged() )
				switch ( $r->get(1) ){
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
			switch ( $r->get(1) ){
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
					$response->send_controller( new \Controller\edu\Students($r, 'edu/students/') );
					die;
				case "teachers":
					$response->send_controller( new \Controller\edu\Teachers($r, 'edu/teachers/') );
					die;
    			default:
					redirect( WEBURL );
			}
			break;

        case "stat":
            switch ( $r->get(1) ){
				case "edu":
					include "protected/modules/stat/edu.module.php";
					break;
				default:
					redirect( WEBURL );
			}
			break;

		case 'admin':
			switch ( $r->get(1) ){
				case "main":
					$response->send_controller( new \Controller\admin\Main($r, 'admin/main/') );
					die;
				case "acl":
					switch ( $r->get(2) ){
						case "users":
							$response->send_controller( new \Controller\acl\Users($r, 'admin/acl/users/') );
							die;
						case "groups":
							$response->send_controller( new \Controller\acl\Groups($r, 'admin/acl/groups/') );
							die;
					}

				default:
					redirect( WEBURL . 'admin/main' );
			}

		// Структура учреждения
		case "org":
			switch ( $r->get(1) ){
				case "departments":
					include "protected/modules/org/departments.module.php";
					break;
				case "employee":
					$response->send_controller( new \Controller\org\Employee($r, 'org/employee/') );
					die();
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
		case 'test':	// тестовая страница
			include "protected/modules/test.module.php";
			break;
		// Controller test!
		case 'some_test':
			$c = new \Controller\SomeTest( $r, 'some_test/' );
			die( $c->run() );
		default:		// по умолчанию - главная страница
			include "protected/modules/index.module.php";
			break;
	}
	
	// Вывод
	Template::output();
}

//	------------
//	Отлов ошибок
//	------------
catch ( CacheException $e ){
	echo "Ошибка кеширования: ". $e->getMessage();
}
//catch ( \Exception\SQL $e ){
//	echo "Ошибка БД: ". $e->getMessage();
//}
//catch ( Exception $e ){
//	echo "Неизвестная ошибка: ". $e->getMessage();
//}
?>