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
				'model' => '\Model\Group',
			),
			'enrollment_date',
			'enrollment_order',
			'dismissal_date',
			'dismissal_order'
		);
}
