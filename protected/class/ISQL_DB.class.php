<?php

interface ISQL_DB{
	
	// ��������� ���������� � ��
	public function __construct( $host, $user, $pass, $name );
	
	
	// ��������� ���������� � ��
	public function __destruct();
	
	
	// ��������� ���������� � ��. ���� ���������� ��� �������, 
	// �� ��������� ������������ � ��������� �����)
	public function connect( $host, $user, $pass, $name );
	
	
	// ������ ��� �����������
	// @return ��� ������ �������( ���� SELECT )
	public function query( $query );
	
	
	// ������ � ������������
	// @return ��� ������ �������( ���� SELECT )
	public function cached_query( $query, $ttl = 120 );
	
	
	// ������� ������ ������� �� ����
	public function reset_cached_query( $query, $time );
	
	
	// ���������� ������������� ������ �� 
	// �������-����������� �� ����������� �������
	public function fetch();
	
	
	// ���������� ��������� ������ - ��������� ���������� �������
	public function get_all();
	
	
	// ���������� ������������� ��������� ����������� ������ � ��
	// (�������� ����������������� ����)
	public function get_insert_id();
	
	
	// ���������� ���������� ����� ���������� 
	// ��������� SELECT ��� SHOW ��������
	public function get_num_rows();
	
	
	// ���������� ���������� ���������� �����  
	// ��������� INSERT, UPDATE, REPLACE ��� DELETE ��������
	public function get_affected_rows();
	
	
	// return last query execution time
	public function get_last_query_time();
	
	
	// return all query execution time
	public function get_time();
	
	
	// return last error occured
	public function get_error();
	
	
	// ������� ������ ��� ���������� � ��
	public function safe( $value );
}

?>