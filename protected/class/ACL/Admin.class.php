<?php
/**
 * Admin access list
 * Класс для полного доступа - Аццкий Одмин
 * Marenin Alex
 * July 2011
 */

namespace Acl;

class Admin extends \Acl\User {
	
	public function __construct(){
		parent::__construct( 0 );
	}
	
	protected function set_access_list(){
		// подключаем драйвер
		$driver = new $this->driver_name;
		
		// устанавливаем все разрешения в true
		$all_permissions = $driver->get_all_permissions();
		foreach ( $all_permissions as $key => $name )
			$this->permissions[$key] = true;
	}
}
?>