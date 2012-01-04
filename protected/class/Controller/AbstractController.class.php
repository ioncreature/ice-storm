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


	protected $response;


	protected $view;


	protected $callback;
	protected $params;



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
						if ( isset($fn['permission']) and !\Auth::$acl->{$fn['permission']} ){
							$this->response = $this->access_denied_response();
							return;
						}
						$this->callback = $fn['method'];
					}
					else
						$this->callback = $fn;
					break;
				}
			}
	}


	protected function run_callback(){
		if ( $this->callback )
			call_user_func_array( array($this, $this->callback), $this->params );
		else
			$this->view->set_404();
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
		$this->run_callback();
		return $this->view->render();
	}
}
