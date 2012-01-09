<?php
/**
 * Model for human
 * @author Marenin Alex
 * November 2011
 */

namespace Model;

class Human extends AbstractModel {
	
	protected $table = 'org_humans';
	protected $fields = array(
		'id', 'first_name', 'middle_name', 'last_name', 'full_name',
		'birth_date', 'photo', 'phone', 'email', 'skype', 'icq', 'facebook', 'vkontakte'
	);
	protected $primary_key = 'id';
	protected $model_name = __CLASS__;


	public function before_save(){
		$this->full_name = $this->last_name .' '. $this->first_name .' '. $this->middle_name;
	}


	public function search_by_name( $name ){
		$this->db_connect();
		$name = $this->db->safe( $name );
		return $this->db->query("
			SELECT
				org_humans.*
			FROM
				org_humans
			WHERE
				org_humans.full_name LIKE '%$name%'
			LIMIT 10
		");
	}


	public function get_all(){
		$this->db_connect();
		return $this->db->query("
			SELECT id, full_name as name FROM org_humans ORDER BY name
		");
	}
}
