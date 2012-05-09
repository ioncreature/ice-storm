<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

namespace Base;

abstract class AbstractApplication extends \Base\AbstractComponent {

	/**
	 * Here app lifecycle
	 * @param array $config
	 */
	public function __construct( array $config = array() ){
		$this->set_config( $config );
		$this->init();
	}


	/**
	 * Run application
	 */
	public function run(){
		$this->pre_init();
		$this->init();
		$this->handle_request();
		$this->send_response();
		$this->uninitialize();
	}

	abstract public function pre_init();



}
