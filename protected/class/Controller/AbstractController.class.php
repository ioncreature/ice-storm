<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
namespace Controller;

use \Response\AbstractResponse as Response;

abstract class AbstractController {

	/**
	 * Instance of \Request\Parser
	 * @var \Request\Parser
	 */
	protected $request;

	/**
	 * @var \View\AbstractView|\View\WebPage|\View\Html|\View\Json
	 */
	protected $view;

	/**
	 * @var string - view class name
	 */
	protected $default_view = DEFAULT_VIEW;

	/**
	 * List of routes from root $path
	 * This param must looks like:
	 * array(
	 * 		'GET' => array(
	 * 			'::int' => 'method_name',
	 * 			'children/::int' => 'get_children'
	 * 		),
	 * 		'POST' => array(
	 * 			'add' => 'some_add_method',
	 *          'edit' => array(
	 *              'method' => 'method_name',
	 *              'permission' => 'permission_name',
	 * 				'view' => '\View\Json'
	 *          ),
	 * 		),
	 *      'DELETE' => array(
	 * 			'all' => array(
	 * 				'method' => 'remove_all',
	 * 				'view' => array(
	 * 					'type' => '\View\WebPage',
	 * 					'layout' => 'layout/admin',
	 * 					'template' => 'page/remove'
	 * 				)
	 * 			)
	 *		),
	 * )
	 * @var array
	 */
	protected $routes = array();

	/**
	 * Root controller path
	 * @var string
	 */
	protected $root_path = '';

	/**
	 * @var int status code
	 */
	protected $status = Response::STATUS_OK;

	// callback variables
	protected $callback;
	protected $params = array();


	/**
	 * @param \Request\Parser $request
	 * @param null|string $root_path
	 */
	public function __construct( \Request\Parser $request, $root_path = null ){
		$this->request = $request;
		$method = $request->method();
		$root_path and $this->root_path = $root_path;

		if ( isset($this->routes[$method]) ){
			$routes = $this->routes[$method];
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path, true );
				if ( $params and $this->request->method() === mb_strtoupper($method) ){
					var_dump( $fn );
					$this->parse_route( $fn );
					$this->params = is_array( $params ) ? $params : $this->params;
					break;
				}
			}
		}

		if ( !$this->view and $this->default_view )
			$this->view = new $this->default_view;
		$this->init();
	}


	/**
	 * Парсит один роут, устанавливает всё что там есть в конфиге
	 * @param array|string $fn
	 */
	private function parse_route( $fn ){
		if ( is_array($fn) ){

			// парсинг прав доступа
			if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} )
				$this->set_status( Response::STATUS_FORBIDDEN );

			// парсинг view
			if ( isset($fn['view']) ){
				if ( is_array($fn['view']) ){
					$this->view = new $fn['view']['type'];
					unset( $fn['view']['type'] );
					foreach ( $fn['view'] as $method => $params )
						$this->view->{'set_'.$method}( $params );
				}
				else
					$this->view = new $fn['view'];
			}

			$this->callback = $fn['method'];
		}
		else
			$this->callback = $fn;
	}


	/**
	 * Set execution status
	 * @param int $status
	 */
	protected function set_status( $status ){
		$this->status = $status;
	}


	/**
	 * Get execution status
	 * @return int
	 */
	public function get_status(){
		return $this->status;
	}


	/**
	 * Returns path to controller
	 * @return string
	 */
	public function get_path(){
		return $this->root_path;
	}


	/**
	 * Return full path to controller(including domain name)
	 * @return string
	 */
	public function get_full_path(){
		return WEBURL . $this->get_path();
	}



	public function redirect( $path ){
		redirect( $path );
	}


	/**
	 * Some controller init code,
	 * redeclare in descendants
	 * @return void
	 */
	public function init(){}


	/**
	 * Run controller and render output
	 * @return string
	 */
	public function run(){
		if ( $this->callback ){
			if ( $this->get_status() === Response::STATUS_OK ){
				$res = call_user_func_array( array($this, $this->callback), $this->params );
				if ( is_array($res) )
					$this->view->add( $res );
			}
		}
		else
			$this->set_status( Response::STATUS_NOT_FOUND );

		$v = $this->view;
		switch ( $this->get_status() ){
			case Response::STATUS_FORBIDDEN:
				return $v->render_access_denied();
			case Response::STATUS_NOT_FOUND:
				return $v->render_not_found();
			case Response::STATUS_ERROR:
				return $v->render_error();
			default: // STATUS_OK
				return $v->render();
		}
	}
}
