<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;
use \Html\Element;

abstract class AbstractForm extends Element implements \I\Exportable {

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


	/**
	 * @var \Model\AbstractModel
	 */
	protected $model;


	/**
	 * @param                           $action
	 * @param string                    $method
	 * @param array                     $attributes
	 * @param \Model\AbstractModel|null $model
	 */
	public function __construct( $action, $method = 'POST', array $attributes = array(), \Model\AbstractModel $model = null ){
		$this->set_attribute( 'action', \Helper\Url::normalize($action) );
		$this->set_attribute( 'method', $this->validate_http_method($method) );
		unset( $attributes['action'], $attributes['method'] );

		// парсинг полей формы
		$this->parse_fields( $this->fields );
		$this->set_model( $model );

		parent::__construct( 'form', $attributes, $this->_fields );

		$this->init();
	}


	/**
	 * Redeclare in descendants
	 */
	public function init(){}


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
			unset( $props['value'], $props['constraints'], $props['attributes'], $props['type'] );

			foreach ( $props as $p => $value )
				$this->_fields[$name]->{'set_'.$p}( $value );
		}
	}


	/**
	 * Returns field object
	 * @param $name
	 * @return \Form\AbstractField
	 * @throws \Exception\Form
	 */
	public function get_field( $name ){
		if ( isset($this->_fields[$name]) )
			return $this->_fields[$name];
		else
			throw new \Exception\Form( "Field $name is not set" );
	}


	/**
	 * Возвращает массив полей формы
	 * @return AbstractField[]
	 */
	public function get_fields(){
		return $this->_fields;
	}


	/**
	 * Fetch fields values from $values
	 * @param array $values
	 * @return AbstractForm
	 */
	public function fetch( array $values ){
		foreach ( $this->fields as $name => $params )
			$this->_fields[$name]->set_value( isset($values[$name]) ? $values[$name] : null );
		return $this;
	}


	/**
	 * Reset field values
	 * @return AbstractForm
	 */
	public function reset(){
		foreach ( $this->get_fields() as $f )
			$f->reset();
		return $this;
	}


	/**
	 * Validate form
	 * @return bool
	 */
	public function validate(){
		foreach ( $this->_fields as $field ){
			if ( !$field->validate() )
				return false;
		}
		return true;
	}


	public function export_errors(){
		$errors = array();


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
	 * @param $field_name
	 * @return string|null
	 */
	public function error( $field_name ){
		return $this->get_field( $field_name )->get_error();
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


	/**
	 * @param \Model\AbstractModel|null $model
	 */
	public function set_model( \Model\AbstractModel $model = null ){
		$this->model = $model;
	}


	public function get_action(){
		return $this->get_attribute( 'action' );
	}
}

