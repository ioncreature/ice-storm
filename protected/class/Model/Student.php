<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;
use \Model\AbstractModel;

class Student extends \Model\AbstractModel {

	protected $table = 'edu_students';
	protected $fields = array(
		'id', 'human_id', 'group_id', 'enrollment_date',
		'enrollment_order', 'dismissal_date', 'dismissal_order'
	);
	protected $primary_key = 'id';
	
}
