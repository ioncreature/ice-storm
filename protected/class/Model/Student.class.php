<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;
use \Model\AbstractModel;

class Student extends \Model\AbstractModel {

	protected static
		$table = 'edu_students',
		$primary_key = 'id',
		$fields = array(
			'id',
			'human_id' => array(
				'foreign_key' => 'id',
				'model' => '\Model\Human',
			),
			'group_id' => array(
				'foreign_key' => 'id',
				'model' => '\Model\Group'
			),
			'enrollment_date' => null,
			'enrollment_order',
			'dismissal_date',
			'dismissal_order',
			'dismissal_reason' => 'none'
		);


	protected function before_save(){
		$fields = array( 'dismissal_date', 'dismissal_order', 'dismissal_reason' );
		foreach ( $fields as $f )
			if ( empty($this->data[$f]) )
				unset( $this->data[$f] );
	}


	public function get_group_students( $group_id ){
		$group_id = (int) $group_id;
		$this->db_connect();

		return $this->db->query("
			SELECT
				edu_students.*,
				org_humans.full_name,
				org_humans.birth_date,
				edu_groups.name as group_name
			FROM
				edu_students
				LEFT JOIN org_humans ON edu_students.human_id = org_humans.id
				LEFT JOIN edu_groups ON edu_students.group_id = edu_groups.id
			WHERE group_id = '$group_id'
			LIMIT 100
		");
	}


	public function get_all_students(){
		$this->db_connect();

		return $this->db->query("
			SELECT
				edu_students.*,
				org_humans.full_name,
				org_humans.birth_date,
				edu_groups.name as group_name
			FROM
				edu_students
				LEFT JOIN org_humans ON edu_students.human_id = org_humans.id
				LEFT JOIN edu_groups ON edu_students.group_id = edu_groups.id
			LIMIT 100
		");
	}


	public function search_by_name( $name ){
		$this->db_connect();
		$name = $this->db->safe( $name );

		return $this->db->query("
			SELECT
				edu_students.*,
				org_humans.full_name,
				org_humans.birth_date,
				edu_groups.name as group_name
			FROM
				edu_students
				LEFT JOIN org_humans ON edu_students.human_id = org_humans.id
				LEFT JOIN edu_groups ON edu_students.group_id = edu_groups.id
			WHERE
				org_humans.full_name LIKE '%$name%'
			LIMIT 100
		");
	}


	public function load_by_human_id( $id ){
		$this->db_connect();
		$id = (int) $id;

		$student_info = $this->db->fetch_query("
			SELECT
				*
			FROM
				edu_students
			WHERE
				edu_students.human_id = '$id'
		");

		if ( $student_info )
			$this->load( $student_info );
		return !!$student_info;
	}
}
