<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;

class Departments extends AbstractService {

	protected $root_path = 'service/department';

	protected $routes = array(
		'get' => array(
			'::int' => array(
				'method' => 'get_department',
				'permission' => 'siski'
			),
			'' => array(
				'method' => 'get_root',
				'permission' => 'siski'
			)
		)
	);

	protected $db = null;

	public function __construct( \Request\Parser $request, $path = null ){
		$this->db = \Fabric::get( 'db' );
		parent::__construct( $request, $path );
	}


	public function get_department( $id ){
		$id = (int) $id;
		$dep = $this->db->fetch_query( "SELECT id, name FROM org_departments WHERE id = '$id'" );
		if ( $dep )
			$dep['children'] = $this->db->query("
				SELECT id, name FROM org_departments WHERE parent_id = '$id'
			");

		return $dep ? $dep : array();
	}


	public function get_root(){
		$root = $this->db->fetch_query( "SELECT id, name FROM org_departments WHERE parent_id = 0 LIMIT 1" );

		if ( $root ){
			$root_id = (int) $root['id'];
			$root['children'] = $this->db->query("
				SELECT id, name FROM org_departments WHERE parent_id = '$root_id'
			");
		}

		return $root;
	}

}
