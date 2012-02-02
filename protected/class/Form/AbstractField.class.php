<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;


abstract class AbstractField extends \Html\Element {

	protected $value;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $constraints = array();

	protected $error_message;

	protected $data_source;

	protected $empty = false;


	/**
	 * Constructor
	 * @param       $name
	 * @param null  $value
	 * @param array $constraints
	 * @param array $attributes
	 */
	public function __construct( $name, $value = null, array $constraints = array(), array $attributes = array() ){
		$this->set_name( $name );
		$this->set_value( $value );
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


	public function set_data_source( /*Some Class*/ $ds ){
		$this->data_source = $ds;
	}


	/**
	 * Adds error message
	 * @param $msg
	 */
	public function set_error( $msg ){
		$this->error_message .= \Helper\Html::encode( $msg );
	}


	public function get_error(){
		return $this->error_message;
	}


	public function set_constraints( array $c ){
		$this->constraints = \Helper\Validator::merge_constraints( $this->constraints, $c );
	}


	public function set_empty_value(){
		$this->empty = true;
	}


	/**
	 * @return boolean
	 */
	public function validate(){
		// make validation
		return \Helper\Validator::validate( $this->value, $this->constraints );
	}
}
