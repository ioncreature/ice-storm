<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;
use \Html\Element;

class AbstractForm extends Element {


	/**
	 * array(
  	 * 		'field2_name' => array(
	 * 			'type' => '\Form\Field\Input'
	 *			'value' => some value,
	 * 			'constraints' => array( 'is_not_empty' ),
	 * 			'attributes' => array( 'some' => 'attribute' )
	 * 		)
	 * )
	 * @var array
	 */
	protected $fields;

	// TODO: добавить обработку субформ
	protected $subforms;

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
	public function __construct( $action, $method = 'post', array $attributes = array() ){
		// TODO: normalize $action URL
		$this->set_attribute( 'action', $action );
		$this->set_attribute( 'method', mb_strtoupper($method) );
		unset( $attributes['action'], $attributes['method'] );

		// парсинг полей формы
		$this->parse_fields( $this->fields );

		parent::__construct( 'form', $attributes, $children );
	}



	public function parse_fields( $fields ){
		foreach ( $fields as $name => $props ){
			unset( $this->fields[$name]);
			$this->fields[$name] = $props;

			$value = isset($props['value']) ? $props['value'] : null;
			$constraints = isset($props['constraints']) ? $props['constraints'] : array();
			$this->fields[$name]['instance'] = new $this->fields[$name]['type']( $name, $value, $constraints );
			// TODO: дописать парсинг полей формы
		}
	}


	public function get_field( $name ){
		if ( !isset($this->fields[$name]) )
			throw new \Exception\Form( "Unknown field with name '$name" );
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
	 * Fetch fields values from $fields_values
	 * @param array $fields_values
	 * TODO: дописать
	 */
	public function fetch( array $fields_values ){
		foreach ( $fields_values as $val ){

		}
	}


	/**
	 * Validate form
	 * TODO: дописать
	 */
	public function validate(){
		foreach ( $this->field as $name => $field )
			if ( !$field->validate() )
				$this->add_error( $name, $field->get_error() );
	}


	/**
	 * Returns field value
	 * @param $field
	 * @param null $value
	 * @return mixed
	 * @throws \Exception\Form
	 */
	public function val( $field, $value = null ){
		if ( isset($this->fields[$field]) )
			return $this->fields[$field]->get_value();
		else
			throw new \Exception\Form( "Field $field is not set" );
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
