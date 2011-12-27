<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;
use \Model\AbstractModel;

class Employee extends \Model\AbstractModel {

	protected $table = 'org_staff';
	protected $fields = array(
		'id', 'state', 'chief', 'work_rate',
		'post', 'phone', 'adoption_date', 'leave_date',
		'human_id' => array(
			'foreign_key' => 'id',
			'model' => 'Human',
			'namespace' => 'Model'
		),
		'department_id' => array(
			'foreign_key' => 'id',
			'model' => 'Department',
			'namespace' => 'Model'
		)
	);
	protected $primary_key = 'id';


	/**
	 * @param int $department_id
	 * @return array
	 */
	public function get_by_department_id( $department_id ){
		$department_id = (int) $department_id;
		$this->db_connect();
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
	
}
