<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Model;

class Group extends \Model\AbstractModel {

	protected static
		$table = 'edu_groups',
		$primary_key = 'id',
		$fields = array(
			'id',
			'name',
			'department_id' => array(
				'foreign_key' => 'id',
				'model' => '\Model\Department',
			),
			'start_date', 'end_date', 'state'
		);


	public function get_groups_and_departments(){
		$this->db_connect();
		return $departments = $this->db->query("
			SELECT
				id, name, parent_id,
				'default' as rel
			FROM org_departments
			WHERE org_departments.state = 'active'
			UNION
			SELECT
				id, name, department_id as parent_id,
				'group' as rel
			FROM
				edu_groups
			WHERE edu_groups.state = 'active'
		");
	}


	public function get_groups_tree(){
		// берем список
		$departments = $this->get_groups_and_departments();
		$dep = array();
		foreach ( $departments as $k => $d ){
			$a = array(
				'id'		=> (int) $d['id'],
				'parent_id' => (int) $d['parent_id'],
				'attr'		=> array(
					'department_id' => (int) $d['id'],
					'rel' => $d['rel']
				)
			);
			if ( $d['rel'] === 'default' )
				$a['state'] = 'open';

		// 	$a['state'] = $d['rel'] === 'department' ? 'open' : 'closed';
			$dep[] = $a;
		}
		$tree = get_departments_tree( $dep );

	}


	public function get_all(){
		$this->db_connect();
		return $this->db->query("
			SELECT *
			FROM edu_groups
			WHERE state = 'on'
		", 'id' );
	}
}
