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
		'id', 'first_name', 'middle_name', 'last_name',
		'birth_date', 'photo', 'phone', 'email', 'skype', 'icq'
	);
	protected $model_name = 'User';
	protected $primary_key = 'id';
}
