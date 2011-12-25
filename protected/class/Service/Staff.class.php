<?php
/**
 * @author Marenin Alex
 *         December 2011
 */
namespace Service;

class Staff extends AbstractService {

	protected $root_path = 'service/staff';

	protected $routes = array(
		'get' => array(
			'' => 'get_all',
			'::int' => 'get_employee',
			'department/::int' => 'get_department_staff',
			'department/::int/recursive' => 'get_department_staff_recursive'
		),
		'put' => array(
			'::int' => 'update_employee'
		)
	);

	protected $db = null;

	public function __construct( \Request\Parser $request, $path = null ){
		$this->db = \Fabric::get( 'db' );
		parent::__construct( $request, $path );
	}


	public function get_all(){
		return $this->db->query("
			SELECT
				org_staff.id, org_staff.post,
				CONCAT( org_humans.last_name, ' ', org_humans.first_name, ' ', org_humans.middle_name ) as name,
				org_departments.name as department,
				org_departments.id as department_id
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			LIMIT 100
		");
	}

	public function get_employee( $id ){
		$id = (int) $id;
		return $this->db->fetch_query("
			SELECT
				org_staff.id, org_staff.post,
				CONCAT( org_humans.last_name, ' ', org_humans.first_name, ' ', org_humans.middle_name ) as name,
				org_departments.name as department,
				org_departments.id as department_id
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			WHERE org_staff.id = '$id'
			LIMIT 1
		");
	}


	public function get_department_staff( $department_id ){
		$department_id = (int) $department_id;
		return $this->db->query("
			SELECT
				org_staff.id, org_staff.post,
				CONCAT( org_humans.last_name, ' ', org_humans.first_name, ' ', org_humans.middle_name ) as name,
				org_departments.name as department,
				org_departments.id as department_id
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			WHERE
				department_id = '$department_id'
		");
	}


	public function get_department_staff_recursive( $department_id ){
		$department_id = (int) $department_id;
		return $this->db->query("
			SELECT *
			FROM
				org_staff
				LEFT JOIN org_humans ON org_humans.id = org_staff.human_id
			WHERE
				org_staff.department_id = '$department_id'
		");
	}


	public function update_employee( $eid ){
		return array( 'status' => true, 'id' => $eid, 'par' => $this->request->get_put() );
	}
}
