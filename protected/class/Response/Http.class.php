<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Response;
use \Response\AbstractResponse as Response;

class Http extends Response {

	protected $http_status = array(
		self::STATUS_OK          => 'HTTP/1.1 200 OK',
		self::STATUS_BAD_REQUEST => 'HTTP/1.1 400 Bad Request',
		self::STATUS_FORBIDDEN   => 'HTTP/1.1 403 Forbidden',
		self::STATUS_NOT_FOUND   => 'HTTP/1.1 404 Not Found',
		self::STATUS_ERROR       => 'HTTP/1.1 500 Internal Server Error'
	);

	protected $headers = array();


	public function send(){
		$this->add_header( $this->http_status[$this->get_status()] );
		$this->send_headers();
		echo $this->response_data;
	}


	public function add_header( $entry ){
		$this->headers[] = $entry;
	}


	public function send_headers(){
		foreach ( $this->headers as $header )
			header( $header );
	}
}
