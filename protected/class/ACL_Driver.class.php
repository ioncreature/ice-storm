<?php
/*
	Abstarct implementation fo ACL driver
	Marenin Alex
	July 2011
	
*/
abstract class ACL_Driver {
	
	// ���������� ��� ����������
	// ������ ����: array( <perm_code_name>: <perm_full_name>, ... )
	abstract public function get_all_permissions();
	
	// ���������� ��� ����� ������� ������������
	// ������ ����: array( <perm_code_name>: true|false, ... )
	abstract public function get_user_permissions( $user_id );
	
	// ���������� ��� ����� ������� ������
	// ������ ����: array( <perm_code_name>: true|false, ... )
	abstract public function get_group_permissions( $group_id );
	
	// ���������� ��� ������ � ������� ������ $user_id
	// ������ ����: array( <group1_id>, <group2_id>, ... )
	abstract public function get_groups( $user_id );
	
	// ���������� ���� �������������, ������� ������ � $group_id
	// ������ ����: array( <user1_id>, <user2_id>, ... )
	abstract public function get_users( $group_id );
	
	// ���������� ������������� ������
	abstract public function get_group_by_name( $group_name );
}
?>