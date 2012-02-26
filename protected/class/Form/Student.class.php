<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;

class Student extends AbstractForm {

	protected $fields = array(
		'id' => array(
			'type' => '\Form\Field\Hidden'
		),
		'human_id' => array(
			'type' => '\Form\Field\Select',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.FilteringSelect',
			),
		),
		'group_id' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( 'not_empty' ),
		),
		'enrollment_date' => array( // дата приема
			'type' => '\Form\Field\Date',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.DateTextBox',
				'data-dojo-props' => ''
			),
			'constraints' => array( 'not_empty' ),
			'error_message' => 'Это обязательное поле'
		),
		'enrollment_order' => array(
			'type' => '\Form\Field\Text',
			'constraints' => array( 'not_empty' )
		),
		'dismissal_date' => array( // дата отчисления
			'type' => '\Form\Field\Date',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.DateTextBox',
				'data-dojo-props' => ''
			)
		),
		'dismissal_order' => array(
			'type' => '\Form\Field\Text',
		),
		'dismissal_reason' => array(
			'type' => '\Form\Field\Select',
			'value' => 'none',
			'options' => array(
				array( 'title' => ' - ', 'value' => 'none' ),
				array( 'title' => 'Окончание учебы', 'value' => 'graduation' ),
				array( 'title' => 'Неуспеваемость', 'value' => 'poor_progress' ),
			)
		)
	);


	public function init(){
		if ( !$this->model )
			$this->model = new \Model\Student();
		$this->fetch( $this->model->export_array() );

		$this->get_field( 'group_id' )->set_options( $this->model->Group->get_all(), 'id', 'name' );
		$this->get_field( 'human_id' )->set_options( $this->model->Human->get_all(), 'id', 'name' );

		if ( !$this->model->enrollment_date )
			$this->get_field( 'enrollment_date' )->set_value( date('Y-m-d') );
	}

}
