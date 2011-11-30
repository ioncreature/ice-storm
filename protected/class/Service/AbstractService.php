<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Service;

abstract class AbstractService {

	/**
	 * Instance of \Request\Parser
	 * This param must looks like:
	 * array(
	 * 		'get' => array(
	 * 			'::int' => 'method_name',
	 * 			'children/::int' => 'get_children'
	 * 		),
	 * 		'post' => array(
	 * 			'add' => 'some_add_method'
	 * 		),
	 * 		...
	 * )
	 *
	 * @var \Request\Parser
	 */
	protected $request;

	/**
	 * Root service path
	 * @var string
	 */
	protected $root_path = '';

	/**
	 * List of path from root $path
	 * @var array
	 */
	protected $routes;

	
	protected $responce;


	public function __construct( \Request\Parser $request, $path = null ){
		$this->request = $request;
		$path and $this->root_path = $path;

		foreach ( $this->routes as $method => &$routes )
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path );
				if ( $params and $this->request->method() === $method ){

					// проверка прав доступа
					if ( is_array($fn) ){
						if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} ){
							$this->responce = $this->access_denied_responce();
							return;
						}
						$callback = $fn['method'];
					}
					else
						$callback = $fn;

					$this->responce =
						call_user_func_array( array($this, $callback), is_array($params) ? $params : array() );
					break;
				}
			}

		if ( !isset($this->responce) )
			$this->responce = $this->empty_responce();
	}


	/**
	 * Calls defined method and send JSON responce(die)
	 * @return void
	 */
	public function responce(){
		die( json_encode($this->responce) );
	}


	public function empty_responce( $msg = '' ){
		return array(
			'status' => false,
			'error' => 'Unknown request path',
			'msg' => $msg
		);
	}


	public function access_denied_responce( $msg = '' ){
		return array(
			'status' => false,
			'error' => 'Access denied',
			'msg' => $msg
		);
	}

}
