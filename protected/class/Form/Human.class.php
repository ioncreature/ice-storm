<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;

class Human extends AbstractForm {

	protected $fields = array(
		'human_last_name' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
			)
		),

		'human_first_name' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
			)
		),

		'human_middle_name' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: true, regExp: '[a-zA-zа-яА-Я-]{2,50}'"
			)
		),

		'human_birth_date' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.DateTextBox'
			)
		),

		'human_phone' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: false, regExp: '\\\\s*[\\+]?[0-9 \\(\\)-]{2,20}\\s*'"
			)
		),

		'human_email' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: false, regExp: '\\\\s*[\\+]?[0-9 \\(\\)-]{2,20}\\s*'"
			)
		),

		'human_skype' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox',
				'data-dojo-props' => "required: false, regExp: '[\\w0-9_-]{2,25}'"
			)
		),

		'human_icq' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox'
			)
		),

		'human_vkcom' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox'
			)
		),

		'human_facebook' => array(
			'type' => '\Form\Field\Text',
			'attributes' => array(
				'data-dojo-type' => 'dijit.form.ValidationTextBox'
			)
		),
	);

	public function init(){
		if ( !$this->model )
			$this->get_field( 'human_birth_date' )->set_value( date('Y-m-d', strtotime('-18 years')) );
	}

}
