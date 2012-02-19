<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

abstract class AbstractApplication {

	/**
	 * Application config lives here
	 * @var array
	 */
	protected $config = array();


	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception\Base
	 */
	public function cfg( $name ){
		if ( isset($this->config[$name]) )
			return $this->config[$name];
		else
			throw new \Exception\Base( "App don't have config entry '$name'" );
	}


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
		$this->handle_request();
		$this->send_response();
	}
}
