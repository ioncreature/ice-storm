<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Model;

class Teacher extends \Model\AbstractModel {

	protected $table = 'edu_teachers';
	protected $fields = array( 'id', 'human_id' );
	protected $primary_key = 'id';

}
