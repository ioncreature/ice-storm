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
		'birth_date', 'photo', 'phone', 'email', 'skype', 'icq'
	);
	protected $primary_key = 'id';
	protected $model_name = __CLASS__;


	public function get_by_name( $first = '', $middle = '', $last = '' ){
		if ( !$first and !$middle and !$last )
			return false;

		$this->db_connect();
		$first = $this->db->safe( $first );
		$middle = $this->db->safe( $middle );
		$last = $this->db->safe( $last );
		$where = $first ? " first_name = '$first'" : '';
		$where .= $where ? $where .' AND ' : '' . $middle ? " middle_name = '$middle'" : '';
		$where .= $where ? $where .' AND ' : '' . $last ? " last_name = '$last'" : '';
		
		$data = $this->db->fetch_query("
			SELECT * FROM {$this->table} WHERE $where
		");

		if ( $data )
			$this->orig_data = $data;

		return $data;
	}


	public function before_save(){
		$this->full_name = $this->last_name .' '. $this->first_name .' '. $this->middle_name;
	}


	public function search_by_name( $name ){
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
		return $this->db->query("
			SELECT id, full_name as name FROM org_humans ORDER BY name
		");
	}
}
