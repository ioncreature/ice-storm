<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;


abstract class AbstractField extends \Html\Element {

	protected $value;

	protected $name;

	protected $constraints = array();

	protected $error_message;


	/**
	 * Constructor
	 * @param $name
	 * @param null $value
	 * @param array $constraints
	 */
	public function __construct( $name, $value = null, array $constraints = array() ){
		$this->name = $name;
		$this->value = $value;
		// TODO: merge constraints
		$this->constraints = $constraints;

		parent::__construct( $this->tag_name, array('type' => '') );
	}


	public function get_value(){
		return $this->value;
	}


	public function set_value( $value ){
		$this->value = $value;
	}


	public function get_name(){
		return $this->name;
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
		$this->constraints = $c;
	}


	/**
	 * @return boolean
	 */
	public function validate(){
		// make validation
		return \Helper\Validator::validate( $this->value, $this->constraints );
	}
}
