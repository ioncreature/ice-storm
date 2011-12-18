<?php
/**
 * @author Marenin Alex
 * November 2011
 */

namespace Request;

/**
 * 
 */
class Parser {

	// HTTP request method
	protected $method = 'get';

	// Request data
	protected $request_data = array();

	// здесь хранится URI
	protected $hash;

	// массив действий - т.е. если $hash равен "/create/img/?text=someText"
	// то в массиве действий будет - array( 'create', 'img' )
	protected $actions = null;


	// Singleton
	private static $instance = null;
    public static function get_instance() {
        if( static::$instance == null )
            static::$instance = new static;
        return static::$instance;
    }


	// MAGIC METHODS ------------------------------------
	public function __construct(){
		//сохраняем hash
		$this->hash = $this->get_uri();
		//заносим данные запроса внутрь экземпляра класса
		foreach ( $_REQUEST as $key => $value ){
			$this->request_data[ $key ] = $value;
		}
		$this->method = strtolower( $_SERVER['REQUEST_METHOD'] );
		$this->actions = $this->get_actions( $this->hash );
	}

	public function __toString(){
		return $this->hash;
	}

	public function __set( $key, $value ){
		return false;
	}

	public function __get( $key ){
		return  array_key_exists( $key, $this->request_data ) ?
				$this->request_data[$key] : null;
	}

	public function __isset( $key ){
		return array_key_exists( $key, $this->request_data );
	}

	public function __unset( $key ){
		unset( $this->request_data[$key] );
	}
	//MAGIC METHODS ------------------------------------


	// создает переменную $hash, хранящую URI
	protected function get_uri(){
		$request_uri = urldecode( $_SERVER['REQUEST_URI'] );
		if ( isset($_GET['hash']) )	// Для Вконтакте
			$hash = $_GET['hash'];
		else if ( mb_substr($request_uri, 0, 2, "utf-8") === "/?" )
			$hash = "";
		else {
			// Убираем из хеша идентификатор сессии
			$hash = substr($request_uri, 1);
			if ( defined('SID') )
				$hash = str_replace( "?". SID , "?", $hash );
		}

		return mb_strtolower( $hash );
	}


	// Возвращает URI запроса
	public function get_hash(){
		return str_replace("?", "", $this->hash);
	}


	// проверка request method
	public function is_get(){
		return $this->method === 'get';
	}


	// проверка request method
	public function is_post(){
		return $this->method === 'post';
	}


	// проверка request method
	public function is_put(){
		return $this->method === 'put';
	}


	// проверка request method
	public function is_delete(){
		return $this->method === 'delete';
	}


	/**
	 * Return current request method
	 * @return string
	 */
	public function method(){
		return $this->method;
	}



	// ------------------------------
	// Методы для работы с действиями
	// ------------------------------

	/*	Возвращает массив действий - т.е. если $hash равен "/create/img/?text=someText"
		то в массиве действий будет - array( 'create', 'img' )
	*/
	public function get_actions( $hash ){

		// удаляю "?" и всё что после
		$pos = mb_strpos( $hash, '?' );
		if ( $pos !== false )
			$hash = mb_substr( $hash, 0, $pos );

		// парсим текст
		$act = explode( '/', $hash );
		$actions = array();
		if ( count($act) != 0 ){
			foreach ( $act as $val )
				if ( $val !== '' )
					$actions[] = $val;
		}
		return $actions;
	}


	// проверка на существование действия
	public function is_set( $index ){
		return isset($this->actions[$index]);
	}


	// метод проверяет, равен ли action с индексом $index значениею $value
	public function is_equal( $index, $value ){

		if ( $this->is_set($index) ){
			if ( is_string($value) )
				return ( mb_strtolower($this->actions[$index], 'UTF-8') == mb_strtolower($value, 'UTF-8') );

			if ( is_int($value) )
				return ( intval($this->actions[$index]) == $value );
		}
		return false;
	}


	/**
	 * проверяет, похоже ли действие на целоe число
	 * @param $index
	 * @return bool
	 */
	public function is_number( $index ){
		return (
			$this->is_set($index) and
			( ($this->actions[$index] === '0') ? true : !!intval($this->actions[$index]) )
		);
	}


	/**
	 * Алиас для \Request\Parser::is_number()
	 * @param $index
	 * @return bool
	 */
	public function is_int( $index ){
		return $this->is_number( $index );
	}


	/**
	 * @param $val
	 * @return void
	 */
	public function is_re( $val ){

	}


	// Переводит "действие" с заданным индексом в число
	// если действие не определено или не переводится, то возвращается 0
	public function to_int( $index ){
		return $this->is_set( $index ) ? intval($this->get( $index )) : 0;
	}


	/**
	 * возвращает строку с действием или '' если действия с таким $index нет
	 * @param $index номер "действия", начиная с 0
	 * @return string
	 */
	public function get( $index ){
		return ( $this->is_set($index) ? $this->actions[$index] : '' );
	}


	/**
	 * Matches request uri with template and returns array of params(if not empty or true) or false
	 * @param $route
	 * @param bool $strict
	 * @return bool|array
	 */
	public function equal( $route, $strict = false ){
		$actions = $this->get_actions( $route );

		if ( $strict and (count($actions) !== count($this->actions)) )
			return false;

		$params = array();
		foreach ( $actions as $i => $val )
			if ( $actions[$i] === '::int' and $this->is_int($i) )
				$params[] = $this->to_int( $i );
			elseif ( $actions[$i] === '::string' and $this->is_set($i) )
				$params[] = $this->get( $i );
			elseif ( !$this->is_equal($i, $actions[$i]) )
				return false;

		return $params ? $params : true;
	}

}
