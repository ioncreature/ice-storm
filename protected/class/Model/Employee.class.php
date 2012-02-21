<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;
use \Model\AbstractModel as Model;

class Employee extends Model {

	protected static $table = 'org_staff';
	protected static $fields = array(
		'id', 'state', 'chief', 'work_rate',
		'post', 'phone', 'adoption_date', 'leave_date',
		'human_id' => array(
			'foreign_key' => 'id',
			'model' => '\Model\Human',
		),
		'department_id' => array(
			'foreign_key' => 'id',
			'model' => '\Model\Department',
		)
	);
	protected static $primary_key = 'id';


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
				org_humans.full_name as name,
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


	public function search_by_name( $name ){
		$this->db_connect();
		$name = $this->db->safe( mb_trim($name) );

		return $this->db->query("
			SELECT
				org_staff.id, org_staff.post,
				org_humans.id as human_id,
				org_humans.full_name as name,
				org_departments.id as department_id,
				org_departments.name as department
			FROM
				org_staff
				INNER JOIN org_humans ON org_humans.id = org_staff.human_id AND org_humans.full_name LIKE '$name%'
				LEFT JOIN org_departments ON  org_departments.id = org_staff.department_id
			WHERE
				org_humans.full_name LIKE '%$name%'
			LIMIT 10
		");
	}


	protected function before_save(){
		$fields = array( 'state', 'chief', 'leave_date', 'human_id' );
		foreach ( $fields as $f )
			if ( empty($this->data[$f]) )
				unset( $this->data[$f] );
	}
	
}
