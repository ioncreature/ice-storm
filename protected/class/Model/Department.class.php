<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace Model;
use \Model\AbstractModel;

class Department extends \Model\AbstractModel {

	protected $table = 'org_departments';
	protected $fields = array(
		'id', 'name', 'state', 'parent_id',
		'create_date', 'edit_date', 'close_date'
	);
	protected $primary_key = 'id';

	public function get_all( $cache = 60 ){
		return $this->db->cached_query("
			SELECT id, name FROM org_departments ORDER BY name
		", $cache );
	}

}
