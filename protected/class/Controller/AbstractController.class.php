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
	 * @var \View\AbstractView
	 */
	protected $view;

	/**
	 * @var string - view class name
	 */
	protected $default_view = '\View\WebPage';

	/**
	 * List of routes from root $path
	 * This param must looks like:
	 * array(
	 * 		'get' => array(
	 * 			'::int' => 'method_name',
	 * 			'children/::int' => 'get_children'
	 * 		),
	 * 		'post' => array(
	 * 			'add' => 'some_add_method',
	 *          'edit' => array(
	 *              'method' => 'method_name',
	 *              'permission' => 'permission_name',
	 * 				'view' => '\View\Json'
	 *          )
	 * 		),
	 *      'put' => ...,
	 *      'delete' => ...
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
		$root_path and $this->root_path = $root_path;

		foreach ( $this->routes as $method => &$routes )
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path, true );
				if ( $params and $this->request->method() === $method ){

					if ( is_array($fn) ){
						// проверка прав доступа
						if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} )
							$this->set_status( Response::STATUS_FORBIDDEN );

						// проверка view
						if ( isset($fn['view']) )
							$this->view = new $fn['view'];

						$this->callback = $fn['method'];
					}
					else
						$this->callback = $fn;

					if ( !$this->view and $this->default_view )
						$this->view = new $this->default_view;

					$this->params = is_array( $params ) ? $params : $this->params;
					break;
				}
			}
		$this->init();
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
	public function get_controller_path(){
		return $this->root_path;
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
