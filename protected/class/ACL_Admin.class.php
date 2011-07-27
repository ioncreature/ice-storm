<?php
/*
	Admin access list
	����� ��� ������� ������� - ������ �����
	Marenin Alex
	July 2011
*/

class ACL_Admin extends ACL{
	
	public function __construct(){
		parent::__construct( 0 );
	}
	
	protected function set_access_list(){
		// ���������� �������
		$driver = new $this->driver_name;
		
		// ������������� ��� ���������� � true
		$all_permissions = $driver->get_all_permissions();
		foreach ( $all_permissions as $key => $name )
			$this->permissions[$key] = true;
	}
}
?>