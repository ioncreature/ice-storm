<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;
use \Controller\AbstractController;

abstract class AbstractService extends \Controller\AbstractController {




	/**
	 * Calls defined method and send JSON response(die)
	 * @return void
	 */
	public function response(){
		die( json_encode($this->response) );
	}


	public function get_response(){
		return $this->response;
	}


	public function empty_response( $msg = '' ){
		return array(
			'status' => false,
			'error' => 'Unknown request path',
			'msg' => $msg
		);
	}


	public function access_denied_response( $msg = '' ){
		return array(
			'status' => false,
			'error' => 'Access denied',
			'msg' => $msg
		);
	}

}
