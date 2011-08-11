<?php
/*
	Класс для авторизации пользователей
	Marenin Alexandr, Arseniev Alexey
	May,July 2011
*/


class Auth{
	
	// класс для разграничения прав доступа
	public		static $acl = false;	
	protected	static $logged = false;
	protected	static $user_id = false;
	
	// Singleton
	private static $instance = null;
	public static function getInstance(){
        if ( static::$instance == null )
            static::$instance = new self;
        return static::$instance;
    }
	
	
	public function __construct(){
		$this->session = SessionStorage::getInstance();
		
		// Проверяем, авторизован ли юзер
		if ( isset($this->session->id, $this->session->acl )){
			static::$logged = true;
			static::$user_id = (int) $this->session->user_id;
			static::$acl = unserialize( $this->session->acl );
		}
		// иначе делаем его "гостем"
		else {
			if ( isset($this->session->acl_guest) )
				static::$acl = unserialize( $this->session->acl_guest );
			else{
				static::$acl = new ACL_Guest();
				$this->session->acl_guest = serialize(static::$acl);
			}
		}
	}

	
	public function logout(){
		if ( static::$logged ){
			unset( $this->session->user_id, $this->session->acl );
			static::$logged = false;
		}
	}
	
	
	public function login( $login, $pass ){
		$db = Fabric::get( 'db' );
		$login = $db->safe( $login );
		$pass = $this->hash( $pass );
		
		$user = $db->fetch_query("
			SELECT * 
			FROM auth_users
			WHERE 
				login = '$login' and
				password = '$pass'
			LIMIT 1
		");
		
		if ( $user['active'] === '0' )
			throw new Exception('Пользователь не активирован');
		
		if ( $user ){
			static::$logged = true;
			static::$user_id = (int) $user['id'];
			static::$acl = new ACL( static::$user_id );
			$this->session->user_id = (int) $user['id'];
			$this->session->acl = serialize( static::$acl );
			unset($this->session->acl_guest);
		}
		
		return static::$logged;
	}
	
	
	protected function hash( $val ){
		return md5( sha1( $val ));
	}
	
	public static function is_logged(){
		return static::$logged;
	}
	public static function get_user_id(){
		return static::$user_id;
	}	
}
?>