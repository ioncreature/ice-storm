<?php
/**
 * @author Marenin Alex
 *         December 2011
 */
namespace Controller;

class AbstractController {

	/**
	 * Instance of \Request\Parser
	 * @var \Request\Parser
	 */
	protected $request;

	/**
	 * Root controller path
	 * @var string
	 */
	protected $root_path = '';

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
	protected $routes;


	/**
	 * @var \View\AbstractView
	 */
	protected $view;

	protected $callback;
	protected $params;
	protected $access = true;


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
							$this->access = false;
						$this->callback = $fn['method'];
					}
					else
						$this->callback = $fn;
					break;
				}
			}
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
		$this->init();
		if ( $this->callback and $this->access ){
			call_user_func_array( array($this, $this->callback), $this->params );
			return $this->view->render( false );
		}
		elseif ( $this->callback and !$this->access )
			return $this->view->render_access_denied();
		else
			return $this->view->render_not_found();
	}
}
