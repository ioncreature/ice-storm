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
			'::int/children' => 'get_children',
			'::int/info' => 'get_info'
		)
	);

	protected $db = null;

	public function __construct( \Request\Parser $request, $path = null ){
		$this->db = \Fabric::get( 'db' );
		parent::__construct( $request, $path );
	}

	public function get_children( $parent_id ){
		$parent_id = (int) $parent_id;
		$children = $this->db->fetch_query( "SELECT * FROM org_departments WHERE parent_id = '$parent_id'" );
		return $children ? $children : array();
	}

	public function get_info( $department_id ){
		$department_id = (int) $department_id;
		$info = $this->db->fetch_query( "SELECT * FROM org_departments WHERE id = '$department_id'" );
		return $info ? $info : array();
	}
	
}
