<?php
/*
	Abstarct implementation fo ACL driver
	Marenin Alex
	July 2011
	
*/
abstract class ACL_Driver {
	
	// Возвращает все разрешения
	// массив вида: array( <perm_code_name>: <perm_full_name>, ... )
	abstract public function get_all_permissions();
	
	// Возвращает все права доступа пользователя
	// массив вида: array( <perm_code_name>: true|false, ... )
	abstract public function get_user_permissions( $user_id );
	
	// Возвращает все права доступа группы
	// массив вида: array( <perm_code_name>: true|false, ... )
	abstract public function get_group_permissions( $group_id );
	
	// Возвращает все группы в которые входит $user_id
	// массив вида: array( <group1_id>, <group2_id>, ... )
	abstract public function get_groups( $user_id );
	
	// Возвращает всех пользователей, которые входят в $group_id
	// массив вида: array( <user1_id>, <user2_id>, ... )
	abstract public function get_users( $group_id );
	
	// Возвращает идентификатор группы
	abstract public function get_group_by_name( $group_name );
}
?>