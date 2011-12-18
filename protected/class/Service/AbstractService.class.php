<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;
use \Controller\AbstractController;

abstract class AbstractService extends \Controller\AbstractController {

	public function __construct( \Request\Parser $request, $root_path = null ){
		$this->request = $request;
		$root_path and $this->root_path = $root_path;

		foreach ( $this->routes as $method => &$routes )
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path, true ); // TODO: make true?
				if ( $params and $this->request->method() === $method ){

					// проверка прав доступа
					if ( is_array($fn) ){
						if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} ){
							$this->response = $this->access_denied_response();
							return;
						}
						$callback = $fn['method'];
					}
					else
						$callback = $fn;

					$this->response =
						call_user_func_array( array($this, $callback), is_array($params) ? $params : array() );
					break;
				}
			}

		if ( !isset($this->response) )
			$this->response = $this->empty_response();
	}


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
