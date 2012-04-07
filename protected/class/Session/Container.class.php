<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

namespace Session;

/**
 * Класс для непосредственной работы с сессией
 * Пример:
 * Берем контейнер
 * $session = \Ice::registry( 'session' )->get_container( 'Test' );
 * Сохраняем данные в сессию:
 * $session->param1 = 100;
 * $session->values = array( 10, 20, 30 );
 * Берем данные из сессии:
 * $some_param = $session->param1;
 */
class Container implements \I\Exportable {

	/** @var string */
	protected $namespace;


	/**
	 * @param string $namespace
	 */
	public function __construct( $namespace ){
		$this->set_namespace( $namespace );
	}


	/**
	 * @return string
	 */
	public function get_namespace(){
		return $this->namespace;
	}


	/**
	 * Устанавливает неймспейс для контейнера сессии
	 * @param $namespace
	 */
	public function set_namespace( $namespace ){
		$this->namespace = $namespace;
		if ( !isset($_SESSION[$namespace]) )
			$_SESSION[$namespace] = array();
	}


	public function __get( $key ){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}


	public function __set( $key, $value ){
		$_SESSION[$this->get_namespace()][$key] = $value;
	}


	public function __isset( $key ){
		return isset( $_SESSION[$this->namespace][$key] );
	}


	/**
	 * @return array
	 */
	public function export_array(){
		return $_SESSION[$this->get_namespace()];
	}


	public function reset(){
		unset( $_SESSION[$this->get_namespace()] );
	}
}
