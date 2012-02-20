<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
namespace Response;

abstract class AbstractResponse {

	/**
	 * Status constants
	 */
	const
		STATUS_OK = 200,
		STATUS_FORBIDDEN = 403,
		STATUS_NOT_FOUND = 404,
		STATUS_ERROR = 500;

	protected
		$response_data,
		$status = self::STATUS_OK;


	/**
	 * Set response status
	 * @param int $status
	 * @return \Response\AbstractResponse
	 */
	public function set_status( $status ){
		$this->status = $status;
		return $this;
	}


	public function get_status(){
		return $this->status;
	}


	public function set_response( $response ){
		$this->response_data = $response;
		return $this;
	}


	public function get_response(){
		return $this->response_data;
	}


	public function send_controller( \Controller\AbstractController $c ){
		$this
			->set_response( $c->run() )
			->set_status( $c->get_status() )
			->send();
	}


	/**
	 * Send response
	 * @abstract
	 */
	abstract public function send();

}
