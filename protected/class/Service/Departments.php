<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;

class Departments extends AbstractService {

	protected $root_path = 'service/departments';

	protected $routes = array(
		'get' => array(
			'::int/children' => 'get_departments',
			'::int/info' => 'get_department_info'
		)
	);

	protected $db;

	public function __construct( \Request\Parser $request, $path = null ){
		parent::__construct( $request, $path );

		$this->db = \Fabric::get( 'db' );
	}

	public function get_department( $parent_id ){
		$children = $this->db->fetch_query( "SELECT * FROM org_departments WHERE parent_id = '$parent_id'" );
		return $children ? $children : array();
	}

	public function get_department_info( $department_id ){
		$children = $this->db->fetch_query( "SELECT * FROM org_departments WHERE parent_id = '$parent_id'" );

	}
	
}
