<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;

class Employee extends AbstractForm {

	protected $fields = array(
		'post' => array(
			'type' => '\Form\Field\Input',
			'value' => '',
			'constraints' => ''
		),
		'departments' => array(
			'type' => '\Form\Field\Select',
			'value' => ''
		)
	);

}
