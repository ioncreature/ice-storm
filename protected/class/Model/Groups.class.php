<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Model;

class Groups extends \Model\AbstractModel {

	protected $table = 'org_groups';
	protected $fields = array(
		'id', 'name', 'department_id',
		'start_date', 'end_date', 'state'
	);
	protected $primary_key = 'id';


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

}
