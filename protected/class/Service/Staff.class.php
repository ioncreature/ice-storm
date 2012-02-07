<?php
/**
 * @author Marenin Alex
 *         December 2011
 */
namespace Service;

class Staff extends AbstractService {

	protected $routes = array(
		'GET' => array(
			'' => 'get_all',
			'::int' => 'get_employee',
			'department/::int' => 'get_department_staff',
			'department/::int/recursive' => 'get_department_staff_recursive',
			'search/::str' => 'search_by_name'
		),
		'PUT' => array(
			'::int' => 'update_employee'
		)
	);

	protected $db = null;
	protected $model = null;


	public function init(){
		$this->db = \Fabric::get( 'db' );
		$this->model = new \Model\Employee();
	}


	public function get_all(){
		$this->view->add( $this->db->query("
			SELECT
				org_staff.id, org_staff.post,
				org_humans.full_name as name,
				org_departments.name as department,
				org_departments.id as department_id
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			LIMIT 100
		"));
	}

	public function get_employee( $id ){
		$id = (int) $id;
		$this->view->add( $this->db->fetch_query("
			SELECT
				org_staff.id, org_staff.post,
				org_humans.full_name as name,
				org_departments.name as department,
				org_departments.id as department_id
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			WHERE org_staff.id = '$id'
			LIMIT 1
		"));
	}


	public function get_department_staff( $department_id ){
		return $this->model->get_by_department_id( (int) $department_id );
	}


	public function search_by_name( $name ){
		return $this->model->search_by_name( $name );
	}


	// TODO
	public function get_department_staff_recursive( $department_id ){
		$this->view->add( array('status' => false, 'msg' => 'method is under development') );
	}


	// TODO
	public function update_employee( $employee_id ){
		$this->view->add( array('status' => false, 'msg' => 'method is under development') );
	}
}
