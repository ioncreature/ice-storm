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
		'id', 'department_id', 'state', 'chief', 'work_rate',
		'post', 'phone', 'adoption_date', 'leave_date',
		'human_id' => array(
			'model' => 'Human',
			'namespace' => 'Model'
		)
	);
	protected $primary_key = 'id';
	
}
