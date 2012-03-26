<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
namespace Controller;

use \Response\AbstractResponse as Response;

// TODO: добавить параметры query, params, pre_run, post_run в настройки роута
// TODO: добавить свойство $default(array), в котором будут дефолтные настройки для роутов
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
	 * @var array - dafault route config, looks like:
	 * array(
	 * 		'method' => 'remove_all',
	 * 		'permission' => 'permission_name',
	 * 		'view' => array(
	 * 			'type' => '\View\WebPage',
	 * 			'layout' => 'layout/admin',
	 * 			'template' => 'page/remove'
	 * 		),
	 * 		'query' => array( 'p1', 'p2' ),
	 *		'params' => array( 'p3', 'p4' ),
	 *		'pre_run' => 'pre_method',
	 *		'post_run' => 'post_method',
	 * )
	 */
	protected $default;

	/**
	 * @var string - view class name
	 */
	protected $default_view;

	/**
	 * List of routes from $root_path
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
	 * 				),
	 * 				'query' => array(),
	 * 				'params' => array(),
	 * 				'before' => 'method_name',
	 * 				'after' => 'method_name',
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

		// TODO: вынести разбор запроса в другое место
		if ( isset($this->routes[$method]) ){
			$routes = $this->routes[$method];
			foreach ( $routes as $route => $fn ){
				$path = ($this->root_path ? $this->root_path .'/' : '') . $route;
				$params = $this->request->equal( $path, true );
				if ( $params and $this->request->method() === mb_strtoupper($method) ){
					$this->parse_route( $fn );
					$this->params = is_array( $params ) ? $params : $this->params;
					break;
				}
			}
		}

		if ( !$this->view ){
			$view = \Ice::config('view.default.type');
			$this->view = new $view;
		}
		$this->init();
	}


	/**
	 * Парсит один роут, устанавливает всё что там есть в конфиге
	 * @param array|string $fn
	 */
	private function parse_route( $fn ){
		// подготовка параметров
		$fn = is_array($fn) ? $fn : array( 'method' => $fn );
		if ( isset($this->default) )
			$fn = array_merge_recursive_distinct( $this->default, $fn );

		// парсинг метода
		$this->callback = $fn['method'];

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

		// парсинг параметров GET
		if ( isset($fn['query']) )
			foreach ( $fn['query'] as $key )
				if ( !isset($this->request->{$key}) ){
					$this->set_status( Response::STATUS_BAD_REQUEST );
					break;
				}

		// парсинг параметров POST
		if ( isset($fn['params']) )
			foreach ( $fn['params'] as $key )
				if ( !isset($this->request->{$key}) ){
					$this->set_status( Response::STATUS_BAD_REQUEST );
					break;
				}

		// парсинг пре- и пост-обработчиков
		if ( isset($fn['before']) )
			$this->before = $fn['before'];
		if ( isset($fn['after']) )
			$this->after = $fn['after'];
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


	/**
	 * @param string $path
	 */
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
			// run before callback
			if ( isset($this->before) )
				call_user_func( array($this, $this->before), $this->callback );

			// run controller action
			if ( $this->get_status() === Response::STATUS_OK ){
				$res = call_user_func_array( array($this, $this->callback), $this->params );
				if ( is_array($res) )
					$this->view->add( $res );
			}

			// run after callback
			if ( isset($this->after) )
				call_user_func( array($this, $this->after), $this->callback, $res );
		}
		else
			$this->set_status( Response::STATUS_NOT_FOUND );

		$v = $this->view;
		switch ( $this->get_status() ){
			case Response::STATUS_BAD_REQUEST:
				return $v->render_bad_request();
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

	// events
	// TODO: придумать как их красиво впихнуть
	public function on_access_denied(){}
	public function on_not_found(){}
	public function on_error(){}
}
