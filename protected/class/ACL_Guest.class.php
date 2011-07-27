<?php
/*
	Guest access list
	����� ��� ��������� �������
	Marenin Alex
	July 2011
*/

class ACL_Guest extends ACL{
	
	public function __construct(){
		parent::__construct( 0 );
	}
	
	protected function set_access_list(){
		// ���������� �������
		$driver = new $this->driver_name;
		
		$guest_group = $driver->get_group_by_name( 'guest' );
		$this->permissions = $driver->get_group_permissions( $guest_group );
	}
}
?>