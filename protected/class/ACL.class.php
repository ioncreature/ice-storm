<?php
/*
	ACL realization by Marenin Alex
	2011
*/

class ACL{
	
	protected $permissions = array();
	protected $permissions_info = array();
	protected $driver_name = 'ACL_MySQL_Driver';
	protected $driver = false;
	
	
	public function __construct( $user_id, $driver_name = 'ACL_MySQL_Driver' ){
		$user_id = (int) $user_id;
		if ( ! $user_id )
			throw new ACL_Exception( "ACL::__constuct : User id is 0, must be >= 1" );
		
		$this->driver = new $driver_name;
		$this->driver_name = $driver_name;
		
		$this->$permissions_info = $this->driver->get_all_permissions();
		$user_p = $this->driver->get_user_permissions();
		
		$groups = $driver->get_groups( $user_id );
		$groups_p = array();
		foreach ( $groups as $group_id ){
			$p = get_group_permissions( $group_id );
			$groups_p = $this->merge( $groups_p, $p );
		}
		
		$this->permissions = $this->merge( $user_p, $groups_p );
		foreach ( $this->permissions_info as $key => $value )
			if ( ! isset($this->permissions[$key]) )
				$this->permissions[$key] = false;
	}
	
	
	private function merge( array $old, array $new ){
		foreach ( $new as $name => $type ){
			if ( isset($old[$name]) )
				$old[$name] = $old[$name] === false ? false : $new[$name];
			else
				$old[$name] = $new[$name];
		}
		return $old;
	}
	
	
	public function __get( $key ){
		if ( isset($permissions[$key]) )
			return $permissions[$key];
		else
			throw new ACL_Exception( "ACL::__get : Unknown permission \"$key\"" );
	}
	
	
	public function __sleep(){
		return array( 'permissions', 'driver_name' );
	}
	
	
	public function __wakeup(){
		$this->driver = new $this->driver_name;
	}
}



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
}



class ACL_MySQL_Driver extends ACL_Driver{
	
	protected $db = false;
	
	public function __construct(){
		$this->db = Fabric::get('db');
	}
	
	
	// Возвращает массив вида: array( <perm_code_name>: <perm_full_name> )
	public function get_all_permissions(){
		$all = $this->db->query( "SELECT * FROM auth_permissions" );
		$perm = array();
		foreach ( $all as $p )
			$perm[ $p['code_name'] ] = $p['full_name'];
		return $perm;
	}
	
	
	// Возвращает все права доступа пользователя
	public function get_user_permissions( $user_id ){
		$user_id = (int) $user_id;
		$user_p = $this->db->query("
			SELECT *
				auth_permissions.code_name,
				auth_user_permissions.type
			FROM 
				auth_user_permissions
				LEFT JOIN auth_permissions ON
					auth_user_permissions.permission_id = auth_permissions.id
			WHERE
				auth_user_permissions.user_id = '$user_id'
		");
		if ( $user_p ){
			$perm = array();
			foreach ( $user_p as $p )
				$perm[ $p['code_name'] ] = $p['type'] === 'allow';
			return $perm;
		}
		else
			return array();
	}
	
	
	// Возвращает все права доступа группы
	public function get_group_permissions( $group_id ){
		$group_id = (int) $group_id;
		$group_p = $this->db->query("
			SELECT *
				auth_permissions.code_name,
				auth_group_permissions.type
			FROM 
				auth_user_permissions
				LEFT JOIN auth_permissions ON
					auth_group_permissions.permission_id = auth_permissions.id
			WHERE
				auth_group_permissions.user_id = '$group_id'
		");
		if ( $group_p ){
			$perm = array();
			foreach ( $group_p as $p )
				$perm[ $p['code_name'] ] = $p['type'] === 'allow';
			return $perm;
		}
		else
			return array();
	}
	
	
	// Возвращает все группы в которые входит $user_id
	public function get_groups( $user_id ){
		$user_id = (int) $user_id;
		$groups = $this->db->query("
			SELECT group_id 
			FROM auth_users_groups 
			WHERE user_id = '$user_id'
		");
		if ( $groups ){
			$res = array();
			foreach ( $groups as $g )
				$res[] = (int) $g['group_id'];
			return $res;
		}
		else 
			return array();
	}
	
	
	// Возвращает всех пользователей, которые входят в $group_id
	public function get_users( $group_id ){
		$group_id = (int) $group_id;
		$users = $this->db->query("
			SELECT group_id 
			FROM auth_users_groups 
			WHERE group_id = '$group_id'
		");
		if ( $users ){
			$res = array();
			foreach ( $groups as $u )
				$res[] = (int) $u['user_id'];
			return $res;
		}
		else 
			return array();
	}
}


class ACL_Exception extends Exception{}
?>