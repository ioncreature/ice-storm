<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;

class Employee extends AbstractForm {

	protected $fields = array(
		'post' => array(
			'type' => '\Form\Field\Text',
			'constraints' => array( 'not_empty', array('regexp', '[a-zA-zа-яА-Я0-9,;\. -]{2,200}') )
		),

		'department_id' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( 'not_empty' )
		),

		'phone' => array(
			'type' => '\Form\Field\Text',
			'value' => ''
		),

		'adoption_date' => array(
			'type' => '\Form\Field\Text',
			'constraints' => array( 'date' )
		),

		'work_rate' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( array( 'in_array', array('full', 'half', 'third', 'quarter') ) ),
			'value' => 'full',
			'options' => array(
				array( 'title' => 'Полная', 'value' => 'full' ),
				array( 'title' => 'Одна вторая', 'value' => 'half' ),
				array( 'title' => 'Одна третья', 'value' => 'third' ),
				array( 'title' => 'Одна четвертая', 'value' => 'quarter' ),
			),
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.Select',
				'class' => 'common_input',
				'required' => 'true'
			)
		),

		'chief' => array(
			'type' => '\Form\Field\Checkbox',
			'value' => 'no',
			'attributes' => array(
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
		$this->fetch( $this->model->export_array() );
		$this->get_field( 'department_id' )->set_options( $deps->get_all(), 'id', 'name' );

		if ( ! $this->model->adoption_date )
			$this->get_field( 'adoption_date' )->set_value( date('Y-m-d') );
	}
}
