<?php
// Парсит запрос к серверу и предоставляет 
// методы для работы с запросом
class RequestParser {
	
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
    public static function getInstance() {
        if( self::$instance == null )
            self::$instance = new self;
        return self::$instance;
    }
	

	// MAGIC METHODS ------------------------------------
	public function __construct(){
		//сохраняем hash
		$this->hash = $this->getURI();
		//заносим данные запроса внутрь экземпляра класса
		foreach ( $_REQUEST as $key => $value ){
			$this->request_data[ $key ] = $value;
		}
		$this->method = strtolower( $_SERVER['REQUEST_METHOD'] );
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
	protected function getURI() {
		if ( isset($_GET['hash']) ) {
			$hash = $_GET['hash'];
		}
		else if ( mb_substr($_SERVER['REQUEST_URI'], 0, 2, "utf-8") === "/?" ){
			$hash = "";
		}
		else {
			// Убираем из хеша идентификатор сессии
			$hash = substr($_SERVER['REQUEST_URI'], 1);
			if ( defined('SID') )
				$hash = str_replace( "?".SID , "?", $hash );
		}
		
		return mb_strtolower( $hash );
	}
	
	
	//
	public function debug(){
		return var_export( $this->request_data, true );
	}
	
	
	//Возвращает URI запроса
	public function getHash(){
		return str_replace("?", "", $this->hash);
	}
	
	
	//отдает все данные запроса (аналог массива $_REQUEST)
	public function getRequestParam(){
		return $this->request_data;
	}
	
	// проверка request method
	public function is_get(){
		return $this->method === 'get';
	}
	// проверка request method
	public function is_post(){
		return $this->method === 'post';
	}

	
	
	// ----------------------------------
	// Методы для работы с путями(Router)
	// ----------------------------------
	/**
	* Maps a pattern to a callable.
	*/
    public function request( $pattern, $to, $method = null ){
		$requirements = array();

		if ( $method )
			$requirements['_method'] = $method;

		$route = new Route($pattern, array('_controller' => $to), $requirements);
		$controller = new Controller($route);
		$this['controllers']->add($controller);

		return $controller;
	} 
	
	/**
	* Maps a GET request to a callable.
	*
	* @param string $pattern Matched route pattern
	* @param mixed $to Callback that returns the response when matched
	*/
	public function on_get( $pattern, $to ){
		return $this->request($pattern, $to, 'GET');
	}

    /**
	* Maps a POST request to a callable.
	*
	* @param string $pattern Matched route pattern
	* @param mixed $to Callback that returns the response when matched
	*/
	public function on_post( $pattern, $to ){
		return $this->request($pattern, $to, 'POST');
	}
	
	
	
	
	
	// ------------------------------
	// Методы для работы с действиями
	// ------------------------------

	/*	Возвращает массив действий - т.е. если $hash равен "/create/img/?text=someText"
		то в массиве действий будет - array( 'create', 'img' )
		@param hash -	если false, то парсится глобальный хеш (RequestParser::hash),
						иначе парсится введенный хэш
	*/
	public function getActions( $hash = false ){
		
		// если уже есть данные о действиях, возвращаем их
		if ( isset($this->actions) and ($hash === false) )
			return $this->actions;
		
		if ( $hash === false )
			$uri = $this->hash;
		else
			$uri = $hash;
			
		// удаляю "?" и всё что после
		$pos = mb_strpos( $uri, '?' );
		if ( $pos !== false )
			$uri = mb_substr( $uri, 0, $pos );
		
		// парсим текст
		$act = explode( '/', $uri );
		$actions = array();
		if ( count($act) != 0 ){
			foreach ( $act as $val )
				if ( $val !== '' )
					$actions[] = $val;
			
			// сохраняем данные парсинга
			if ( $hash === false )
				$this->actions = $actions;
		}
		return $actions;
	}
	
	
	// проверка на существование действия
	public function is_set( $index ){
		if ( $this->actions === null )
			$this->getActions();
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
	
	
	// проверяет, похоже ли действие на целочисленную строку
	public function is_number( $index ){
		return ( 
			$this->is_set($index) and 
			( ($this->actions[$index] === '0') ? true : !!intval($this->actions[$index]) )
		);
	}
	
	
	// Алиас для  RequestParser::is_number()
	public function is_int( $index ){
		return $this->is_number( $index );
	}
	
	
	// Переводит "действие" с заданным индексом в число
	// если действие не определено или не переводится, то возвращается 0
	public function to_int( $index ){
		return $this->is_set( $index ) ? intval($this->get( $index )) : 0;
	}
	
	
	// возвращает строку с действием или '' если действия с таким $index нет
	public function get( $index ){
		return ( $this->is_set($index) ? $this->actions[$index] : '' );
	}
	
}
?>