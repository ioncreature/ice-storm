<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;
use \Html\Element;
use \Helper\Validator;
use \Helper\Html;

abstract class AbstractField extends Element {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $constraints = array();

	protected $value;

	protected $default_value;

	protected $error_message = 'Введено некорректное значение';

	protected $has_error = false;


	/**
	 * Constructor
	 * @param       $name
	 * @param null  $value
	 * @param array $constraints
	 * @param array $attributes
	 */
	public function __construct( $name, $value = null, array $constraints = array(), array $attributes = array() ){
		$this->set_name( $name );
		$this->set_value( $value === null ? $value : $this->get_default_value() );
		$this->set_constraints( $constraints );
		parent::__construct( $this->tag_name, $attributes );
		$this->init();
	}


	/**
	 * Calls after $this->_constructor()
	 */
	public function init(){}


	public function get_value(){
		return $this->value;
	}


	public function set_value( $value ){
		$this->value = $value;
	}


	public function set_name( $name ){
		$this->name = $name;
	}


	public function get_name(){
		return $this->name;
	}


	/**
	 * Adds error message
	 * @param $msg
	 */
	public function set_error_message( $msg ){
		$this->error_message = Html::encode( $msg );
	}


	public function get_error_message(){
		return $this->has_error ? $this->error_message : null;
	}


	public function set_constraints( array $c ){
		$this->constraints = Validator::merge_constraints( $this->constraints, $c );
	}


	/**
	 * Check field value on constraints
	 * @return boolean
	 */
	public function validate(){
		// make validation
		$res = Validator::validate( $this->value, $this->constraints );
		if ( !$res )
			$this->has_error = true;

		return $res;
	}


	public function reset(){
		$this->set_value( $this->get_default_value() );
	}


	public function get_default_value(){
		return $this->default_value;
	}


	public function set_default_value( $value ){
		$this->default_value = $value;
	}
}
