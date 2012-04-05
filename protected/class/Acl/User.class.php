<?php
/**
 * Acl realization
 * Marenin Alex
 * July 2011
 */

namespace Acl;

class User {
	
	/**
	 * access list array('rule' => true|false, ...)
	 * @var array
	 */
	protected $permissions = array();
	
	protected $driver_name;
	
	protected $user_id = null;
	
	
	public function __construct( $user_id, $driver_name = 'Acl\Driver\MySQL' ){
		$this->user_id = (int) $user_id;
		$this->driver_name = $driver_name;
		
		// нарезаем права
		$this->set_access_list();
	}
	

	/**
	 * Метод описывает правила по которым нарезаются права
	 * Для классов-потомков необходимо переопределить!
	 * @throws \Exception\Acl
	 */
	protected function set_access_list(){
		// подключаем драйвер
		$driver = new $this->driver_name;
		
		if ( ! $this->user_id )
			throw new \Exception\Acl( "\\Acl\\User::set_access_list : User id is 0, must be >= 1" );
		
		// Получаем групповые разрешения
		$groups = $driver->get_groups( $this->user_id );
		$groups_p = array();
		foreach ( $groups as $group_id ){
			$p = $driver->get_group_permissions( $group_id );
			$groups_p = $this->merge( $groups_p, $p );
		}
		
		// Получаем пользовательские разрешения
		$user_p = $driver->get_user_permissions( $this->user_id );
		$this->permissions = $this->merge( $user_p, $groups_p );
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
	
	
	/**
	 * Шоткат для метода check
	 * @param string $name
	 * @return bool
	 */
	public function __get( $name ){
		return $this->check( $name );
	}


	public function __sleep(){
		return array( 'permissions', 'driver_name' );
	}


	/**
	 * Основной метод - возвращает true|false в зависимости
	 * от того, есть ли разрешение с именем $name
	 * @param string $name
	 * @return bool
	 */
	public function check( $name ){
		return isset($this->permissions[$name]) ? $this->permissions[$name] : false;
	}
}