<?php
/**
 * @author Marenin Alex
 *         December 2011
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
	 *              'permission' => 'permission_name'
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

	protected $callback;
	protected $params = array();


	public function __construct( \Request\Parser $request, $root_path = null ){
		$this->request = $request;
		$root_path and $this->root_path = $root_path;

		foreach ( $this->routes as $method => &$routes )
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path, true );
				if ( $params and $this->request->method() === $method ){

					// проверка прав доступа
					if ( is_array($fn) ){
						if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} )
							$this->set_status( Response::STATUS_FORBIDDEN );
						$this->callback = $fn['method'];
					}
					else
						$this->callback = $fn;
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


	public function get_status(){
		return $this->status;
	}


	/**
	 * Some controller init code,
	 * redeclare in descendants
	 */
	public function init(){}


	/**
	 * Renders output
	 * @return string
	 */
	public function render(){
		if ( $this->callback and $this->get_status() === Response::STATUS_OK )
			call_user_func_array( array($this, $this->callback), $this->params );

		$v = $this->view;
		switch ( $this->get_status() ){
			case Response::STATUS_FORBIDDEN:
				return $v->render_access_denied();
			case Response::STATUS_NOT_FOUND:
				return $v->render_not_found();
			case Response::STATUS_ERROR:
				return $v->render_error();
			// Response::STATUS_OK
			default:
				return $v->render();
		}
	}
}
