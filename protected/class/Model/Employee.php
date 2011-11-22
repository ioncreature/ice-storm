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
		'id', 'human_id', 'department_id', 'state',
		'chief', 'work_rate', 'post', 'phone', 'adoption_date', 'leave_date'
	);
	protected $primary_key = 'id';
	
}
