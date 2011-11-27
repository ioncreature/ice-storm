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

		// TODO: добавить парсинг $this->routes и вызов соответствующего метода
		foreach ( $this->routes as $method => &$routes )
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path );
				if ( $params and $this->request->method() === $cb['type'] ){
					$this->responce =
						call_user_func_array( array($this, $fn), is_array($params) ? $params : array() );
					break;
				}
			}
		$this->responce or $this->responce = $this->empty_responce();
	}


	/**
	 * Calls defined method and send JSON responce
	 * @param $method
	 * @return void
	 */
	protected function responce( $method ){
		die( json_encode($this->responce) );
	}

	public function empty_responce(){
		return array( 'status' => false, 'error' => 'Unknown request path' );
	}

}
