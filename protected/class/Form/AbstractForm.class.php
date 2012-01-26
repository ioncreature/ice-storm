<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;
use \Html\Element;

abstract class AbstractForm extends Element {

	/**
	 * array(
  	 * 		'field2_name' => array(
	 * 			'type' => '\Form\Field\Input'
	 *			'value' => some value,
	 * 			'constraints' => array( 'is_not_empty' ),
	 * 			'attributes' => array( 'some' => 'attribute' ),
	 * 			'data_source' => ''
	 * 		)
	 * )
	 * @var array
	 */
	protected $fields = array();

	// TODO: добавить обработку субформ
	protected $subforms;

	/**
	 * Array of field instances
	 * @var \Form\AbstractField[]
	 */
	protected $_fields = array();


	// TODO: добавить в форму методы для прямой работы с моделью
	protected $model;


	/**
	 * @param        $action
	 * @param string $method
	 * @param array  $attributes
	 */
	public function __construct( $action, $method = 'POST', array $attributes = array() ){
		// TODO: normalize $action URL
		$this->set_attribute( 'action', $action );
		$this->set_attribute( 'method', $this->validate_http_method($method) );
		unset( $attributes['action'], $attributes['method'] );

		// парсинг полей формы
		$this->parse_fields( $this->fields );

		parent::__construct( 'form', $attributes, $this->_fields );
	}


	private function validate_http_method( $method ){
		switch ( mb_strtoupper(mb_trim($method)) ){
			case 'GET': return 'GET';
			case 'PUT': return 'PUT';
			case 'DELETE': return 'DELETE';
			default: return 'POST';
		}
	}


	/**
	 * @param $fields
	 * @return array
	 */
	public function parse_fields( $fields ){
		foreach ( $fields as $name => $props ){
			unset( $this->fields[$name] );
			$this->fields[$name] = $props;

			$value = isset($props['value']) ? $props['value'] : null;
			$constraints = isset($props['constraints']) ? $props['constraints'] : array();
			$attributes = isset($props['attributes']) ? $props['attributes'] : array();
			$this->_fields[$name] = new $this->fields[$name]['type']( $name, $value, $constraints, $attributes );
		}
	}


	/**
	 * Returns field object
	 * @param $name
	 * @return \Form\Field
	 * @throws \Exception\Form
	 */
	public function get_field( $name ){
		if ( isset($this->_fields[$name]) )
			return $this->_fields[$name]['instance'];
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 * Fetch fields values from $fields_values
	 * @param array $fields_values
	 */
	public function fetch( array $fields_values ){
		foreach ( $fields_values as $name => $value )
			if ( isset($this->fields[$name]) )
				$this->_fields[$name]->set_value( $value );
	}


	/**
	 * Validate form
	 * @return bool
	 */
	public function validate(){
		$valid = true;
		foreach ( $this->_fields as $name => $field ){
			if ( !$field->validate() )
				$valid = false;
		}
		return $valid;
	}


	/**
	 * Returns field value
	 * @param $field - Field name
	 * @return mixed
	 * @throws \Exception\Form
	 */
	public function val( $field ){
		if ( isset($this->_fields[$field]) )
			return $this->_fields[$field]->get_value();
		else
			throw new \Exception\Form( "Field $field is not set" );
	}


	/**
	 * @param string $name field name
	 * @return mixed
	 * @throws \Exception\Form
	 */
	public function msg( $name ){
		if ( isset($this->_fields[$name]) )
			return $this->_fields[$name]->get_error();
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 * Exports form fields as array
	 * @return array
	 */
	public function export_array(){
		$out = array();
		foreach ( $this->_fields as $field )
			$out[$field->get_name()] = $field->get_value();
		return $out;
	}
}

