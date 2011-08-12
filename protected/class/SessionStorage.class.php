<?
// Класс хранилища
// Использование сессий для хранения данных
class SessionStorage implements ISession{
	// данные сессии
	protected $data = array();
	
	// SID
	private $sid = null;
	
	protected $cache = null;
	
	
	// Singleton
	// Hold an instance of the class
    private static $instance;
	public static function get_instance( $sid = false, $renew = true ){
		if ( !isset(self::$instance) ){
            $c = __CLASS__;
            self::$instance = new $c( $sid, $renew );
        }
        return self::$instance;
	}
	
	
	
	// MAGIC METHODS ------------------------------------
	public function __construct( $sid, $renew ){
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); 
		if (!ini_get('session.auto_start')) {
			ini_set('session.use_cookies', 1);
			session_name("s");
			session_start();
		} 
		$this->data = $_SESSION;
		
		$this->sid = $sid;
	}
	
	public function __set( $key, $value ){
		$this->data[$key] = $value;
		$_SESSION[$key] = $value;
		return true;
	}
	
	public function __get( $key ){
		if ( $key === 'sid' ) 
			return $this->sid;
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	public function __isset( $key ){
		return isset( $this->data[$key] );
	}
	
	public function __unset( $key ){
		unset( $this->data[$key] );
		unset( $_SESSION[$key] );
	}
	
	public function __toString(){
		return var_export( $this->data, true );
	}
	
	public function __destruct(){
		//$this->cache->set( 'user_'. $this->sid, $this->data, 0, 15*60 );
		session_write_close();
	}
	//MAGIC METHODS ------------------------------------
	
	
	
	//установка всех данных сессии
	public function set_all( $value ){
		if ( is_array($value) )
			foreach ( $value as $key => $v ){
				$this->data[$key] = $value[$key];
				$_SESSION[$key] = $value[$key];
			}
		return true;
	}
	
	
	
	//взять все данные сессии
	public function get_all(){
		return $this->data;
	}
	
	
	//удаление всей сессии
	public function unset_all(){
		$this->data = array();
		return session_unset();
	}
	
}

?>