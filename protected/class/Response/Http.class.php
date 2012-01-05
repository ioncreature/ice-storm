<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Response;
use \Response\AbstractResponse as Response;

class Http extends Response {

	protected $http_status = array(
		self::STATUS_OK        => 'HTTP/1.1 200 OK',
		self::STATUS_FORBIDDEN => 'HTTP/1.1 403 Forbidden',
		self::STATUS_NOT_FOUND => 'HTTP/1.1 404 Not Found',
		self::STATUS_ERROR     => 'HTTP/1.1 500 Internal Server Error'
	);

	public function send(){
		header( $this->http_status[$this->get_status()] );
		echo $this->response_data;
	}
}
