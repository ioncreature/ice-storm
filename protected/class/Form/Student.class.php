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
			'type' => '\Form\Field\Select'
		),
		'group_id' => array(
			'type' => '\Form\Field\Select',
			'constraints' => array( 'not_empty' )
		),
		'enrollment_date' => array( // дата приема
			'type' => '\Form\Field\Date',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.DateTextBox',
				'data-dojo-props' => ''
			)
		),
		'enrollment_order' => array(
			'type' => '\Form\Field\Text',
			'constraints' => array( 'not_empty' )
		),
		'dismissal_date' => array( // дата приема
			'type' => '\Form\Field\Date',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.DateTextBox',
				'data-dojo-props' => ''
			)
		),
		'dismissal_order' => array(
			'type' => '\Form\Field\Text',
			'constraints' => array( 'not_empty' )
		)
	);


	public function init(){
		// TODO: установить список групп

	}

}
