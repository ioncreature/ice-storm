<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;
use \Html\Element;

class Form extends Element {

	protected $fields;

	public $error_tpl_start = '<div class="error">';
	public $error_tpl_separator = '<br/>';
	public $error_tpl_end = '</div>';

	// TODO: добавить в форму методы для прямой работы с моделью
	protected $model;


	/**
	 * @param $action
	 * @param string $method
	 * @param array $attributes
	 * @param array $children
	 */
	public function __construct( $action, $method = 'post', $attributes = array(), $children = array() ){
		// TODO: normalize URL
		$this->set_attribute( 'action', $action );
		$this->set_attribute( 'method', mb_strtoupper($method) );
		unset( $attributes['action'], $attributes['method'] );
		parent::__construct( 'form', $attributes, $children );
	}


	/**
	 * Returns field object
	 * @param $name
	 * @return mixed
	 * @throws \Exception\Form
	 */
	public function field( $name ){
		if ( isset($this->fields[$name]) )
			return $this->fields[$name];
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 * Validate form
	 */
	public function validate(){
		foreach ( $this->field as $name => $field )
			if ( !$field->validate() )
				$this->add_error( $name, $field->get_error() );
	}


	/**
	 * Returns textual representation
	 * @param $field
	 * @param null $value
	 */
	public function val( $field, $value = null ){
		if ( isset($this->fields[$name]) )
			return $this->fields[$name]->get_value();
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 *
	 * @param $name field name
	 * @return mixed
	 * @throws \Exception\Form
	 */
	public function msg( $name ){
		if ( isset($this->fields[$name]) )
			return $this->fields[$name]->get_error();
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 * Exports form fields as array
	 * @return array
	 */
	public function export_array(){
		$out = array();
		foreach ( $this->fields as $field )
			$out[$field->get_name()] = $field->get_value();
		return $out;
	}
}
