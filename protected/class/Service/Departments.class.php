<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;
use \Service\AbstractService as Service;

class Departments extends Service {

	protected $routes = array(
		'GET' => array(
			'::int' => array(
				'method' => 'get_department',
				'permission' => 'department_read'
			),
			'' => array(
				'method' => 'get_root',
				'permission' => 'department_read'
			)
		),
		'DELETE' => array(
			'::int' => array(
				'method' => 'delete_department',
				'permission' => 'department_delete'
			)
		)
	);

	protected $model = null;

	public function init(){
		$this->model = new \Model\Department();
	}


	public function get_department( $id ){
		$id = (int) $id;
		$dep = $this->model->get_by_id( $id )->export_array();
		if ( $dep )
			$dep['children'] = $this->model->get_children();

		$this->view->add( $dep ? $dep : array() );
	}


	public function get_root(){
		$root = $this->model->get_root();
		if ( $root )
			$root['children'] = $this->model->get_children( (int) $root['id'] );

		$this->view->add( $root ? $root : array() );
	}

}
