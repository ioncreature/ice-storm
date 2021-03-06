<?php
/**
 * MySQL implementation fo \Acl\Driver
 * Marenin Alex
 * July 2011
 */

namespace Acl\Driver;

class MySQL extends \Acl\Driver{
	
	// Singleton
	protected static $instance = null;
	
	protected $db = false;
	
	public function __construct(){
		$this->db = \Db\Factory::get('db');
	}
	
	
	// Возвращает массив вида: array( <perm_name>: <perm_description> )
	public function get_all_permissions(){
		$all = $this->db->query( "SELECT * FROM auth_permissions" );
		$perm = array();
		foreach ( $all as $p )
			$perm[ $p['name'] ] = $p['description'];
		return $perm;
	}
	
	
	// Возвращает все права доступа пользователя
	public function get_user_permissions( $user_id ){
		$user_id = (int) $user_id;
		$user_p = $this->db->query("
			SELECT 
				auth_permissions.name,
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
				$perm[ $p['name'] ] = $p['type'] === 'allow';
			return $perm;
		}
		else
			return array();
	}
	
	
	// Возвращает все права доступа группы
	public function get_group_permissions( $group_id ){
		$group_id = (int) $group_id;
		$group_p = $this->db->query("
			SELECT
				auth_permissions.name,
				auth_group_permissions.type
			FROM 
				auth_group_permissions
				LEFT JOIN auth_permissions ON
					auth_group_permissions.permission_id = auth_permissions.id
			WHERE
				auth_group_permissions.group_id = '$group_id'
		");
		if ( $group_p ){
			$perm = array();
			foreach ( $group_p as $p )
				$perm[ $p['name'] ] = $p['type'] === 'allow';
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
			foreach ( $users as $u )
				$res[] = (int) $u['user_id'];
			return $res;
		}
		else 
			return array();
	}
	
	
	// Возвращает идентификатор группы
	public function get_group_by_name( $name ){
		$name = $this->db->safe( $name );
		$group = $this->db->fetch_query("SELECT id FROM auth_groups WHERE code_name = '$name'");
		return $group ? intval($group['id']) : false;
	}
	
	
	// Возвращает список групп, в которых находится пользователь
	// @returns array( 'group_id' => 'group_name', ... )
	public function get_user_groups( $user_id ){
		$user_id = (int) $user_id;
		
		$this->db->query("	
			SELECT 
				auth_users_groups.group_id,
				auth_groups.code_name as name
			FROM 
				auth_users_groups
				LEFT JOIN auth_groups ON auth_groups.id = auth_users_groups.group_id
			WHERE user_id = '$user_id'
		");
		$out = array();
		while ( $group = $this->db->fetch() )
			$out[ (int) $group['group_id'] ] = $group['name'];
		
		return count( $out ) ? $out : false;
	}
	
	
	// Возвращает список идентификаторов пользователей, входящих в группу $group_id
	// @returns array( Int user1_id, ... )
	public function get_group_users( $group_id ){
		$group_id = (int) $group_id;
		
		$this->db->query( "SELECT user_id FROM auth_users_groups WHERE group_id = '$group_id'" );
		$out = array();
		while ( $user = $this->db->fetch() )
			$out[] = (int) $user['user_id'];
	}
	
	
	
	// раздел для РАЗРЕШЕНИЙ
	
	// Добавляет новое правило
	// Возвращает true|false
	public function add_permission( $name, $description ){
		$name = $this->db->safe( $name );
		if ( mb_strlen($name) > 50 )
			throw new \Acl\Exception('\Acl\Driver\MySQL::add_permission : Permission name too long ( >50 characters)');
			
		$description = mb_substr( $this->db->safe($description), 0, 100 );
		
		$test = $this->db->fetch_query( "SELECT * FROM auth_permissions WHERE name = '$name'" );
		if ( !$test )
			$this->db->insert( 'auth_permissions', array(
				'name' => $name,
				'description' => $description
			));
		else
			throw new \Acl\Exception("\\Acl\\Driver\\MySQL::add_permission : Permission \"$name\" already exists");
	}
	
	
	// USER/GROUP BIND section
	
	// Добавляет пользователя в группу
	// Возвращает true|false
	public function bind_user_group( $user_id, $group_id ){
		$user_id = (int) $user_id;
		$group_id = (int) $group_id;
		
		$link = $this->db->fetch_query("
			SELECT * 
			FROM 
				auth_users_groups
			WHERE 
				user_id = '$user_id' and 
				group_id = '$group_id'
		");
		if ( ! $link )
			$this->db->insert( 'auth_users_groups', array(
				'user_id' => $user_id,
				'group_id' => $group_id
			));
		return true;
	}
	
	// Убирает пользователя из группы
	// Возвращает true|false
	public function unbind_user_group( $user_id, $group_id ){
		$user_id = (int) $user_id;
		$group_id = (int) $group_id;
		
		$this->db->query("
			DELETE FROM auth_users_groups
			WHERE	
				user_id = '$user_id' and 
				group_id = '$group_id'
			LIMIT 1
		");
		return true;
	}
	
	
	// Проверка есть ли пользователь в группе
	// Возвращает true|false
	public function user_in_group( $user_id, $group_id ){
		$user_id = (int) $user_id;
		$group_id = (int) $group_id;
		
		$q = $this->db->fetch_query("
			SELECT id 
			FROM auth_users_groups
			WHERE 
				group_id = '$group_id' and
				user_id = '$user_id'
			LIMIT 1
		");
		return !!$q;
	}
}

?>