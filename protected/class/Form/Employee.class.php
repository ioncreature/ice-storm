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
			'constraints' => array( 'not_empty', array('regexp', '[a-zA-zа-яА-Я0-9,;\. -]{2,200}') )
		),

		'department_id' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( 'not_empty' )
		),

		'phone' => array(
			'type' => '\Form\Field\Input',
			'value' => ''
		),

		'adoption_date' => array(
			'type' => '\Form\Field\Input',
			'constraints' => array( 'date' )
		),

		'work_rate' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( array( 'in_array', array('full','half','third','quarter') ) ),
			'value' => 'full'
		),

		'chief' => array(
			'type' => '\Form\Field\Checkbox',
			'value' => 'no',
			'attributes' => array(
				'type' => "checkbox",
				'name' => "chief",
				'data-dojo-type' => "dijit.form.CheckBox"
			),
			'checked_value' => 'yes',
			'unchecked_value' => 'no'
		)
	);


	public function init(){
		if ( !$this->model )
			$this->model = new \Model\Employee();
		$deps = $this->model->Department;
		$this->get_field( 'department_id' )->set_options( $deps->get_all(), 'id', 'name' );
		$this->get_field( 'adoption_date' )->set_value( date('Y-m-d') );
	}

}
