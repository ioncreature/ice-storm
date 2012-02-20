<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace Model;
use \Model\AbstractModel as Model;

class Department extends Model {

	protected static $table = 'org_departments';
	protected static $fields = array(
		'id', 'name', 'state', 'parent_id',
		'create_date', 'edit_date', 'close_date'
	);
	protected static $primary_key = 'id';

	public function get_all(){
		$this->db_connect();
		return $this->db->query("
			SELECT id, name
			FROM org_departments
			ORDER BY name
		");
	}


	public function get_root(){
		$this->db_connect();
		$this->load( $this->db->fetch_query("
			SELECT *
			FROM org_departments
			WHERE parent_id = 0
			LIMIT 1
		"));
		return $this->export_array();
	}


	public function get_children( $department_id = null ){
		$this->db_connect();
		if ( !$department_id and $this->exists() )
			$department_id = $this->id;
		else
			$department_id = (int) $department_id;

		return $this->db->query("
			SELECT id, name
			FROM org_departments
			WHERE parent_id = '$department_id'
		");
	}

}
