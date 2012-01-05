<?php
/**
 * @author Marenin Alex
 *         December 2011
 */
namespace Response;

abstract class AbstractResponse {

	/**
	 * Status constants
	 */
	const STATUS_OK = 200;
	const STATUS_FORBIDDEN = 403;
	const STATUS_NOT_FOUND = 404;
	const STATUS_ERROR = 500;

	protected $response_data;
	protected $status = self::STATUS_OK;


	/**
	 * Set response status
	 * @param $status
	 */
	public function set_status( $status ){
		$this->status = $status;
	}

	public function set_response( $response ){
		$this->response_data = $response;
	}

	public function get_response(){
		return $this->response_data;
	}


	/**
	 * Send response
	 * @abstract
	 */
	abstract public function send();

}
