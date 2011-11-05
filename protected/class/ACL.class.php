<?php
/*
	ACL realization
	Marenin Alex
	July 2011
*/

class ACL{
	
	// access list array('rule' => true|false, ...)
	protected $permissions = array();
	
	protected $driver_name;
	
	protected $user_id = null;
	
	
	public function __construct( $user_id, $driver_name = 'ACL_MySQL_Driver' ){
		$this->user_id = (int) $user_id;
		$this->driver_name = $driver_name;
		
		// нарезаем права
		$this->set_access_list();
	}
	
	
	// Метод описывает правила по которым нарезаются права
	// Для классов-потомков необходимо переопределить!
	protected function set_access_list(){
		// подключаем драйвер
		$driver = new $this->driver_name;
		
		if ( ! $this->user_id )
			throw new ACL_Exception( "ACL::set_access_list : User id is 0, must be >= 1" );
		
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
	
	
	// основной метод - возвращает true|false в зависимости 
	// от того, есть ли разрешение с именем $name
	public function __get( $name ){
		return isset($this->permissions[$name]) ? $this->permissions[$name] : false;
	}
	
	
	public function __sleep(){
		return array( 'permissions', 'driver_name' );
	}
	
	
	public function __wakeup(){
	}
}
?>