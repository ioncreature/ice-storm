<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Form;


abstract class AbstractField extends \Html\Element {

	protected $value;

	protected $name;

	protected $error_message;

	/**
	 * Constructor
	 * @param $name
	 * @param $value
	 */
	public function __construct( $name, $value = null ){
		$this->name = $name;
		$this->value = $value;
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


	/**
	 * @abstract
	 * @return boolean
	 */
	abstract public function validate();
}
