<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;

class Teacher extends \Model\AbstractModel {

	protected static
		$table = 'edu_teachers',
		$primary_key = 'id',
		$fields = array( 'id', 'human_id' );

}
