<?php
/**
 *	Класс для авторизации пользователей
 *	Marenin Alexandr, Arseniev Alexey
 *	May,July 2011
*/


class Auth{
	
	// класс для разграничения прав доступа
	public		static $acl = false;	
	protected	static $logged = false;
	protected	static $user = null;
	
	
	// Singleton
	private static $instance = null;
	public static function get_instance(){
        if ( static::$instance == null )
            static::$instance = new self;
        return static::$instance;
    }
	
	
	public function __construct(){
		$this->session = SessionStorage::get_instance();
		
		// Проверяем, авторизован ли юзер
		if ( isset($this->session->user, $this->session->acl) ){
			static::$logged = true;
			static::$user = unserialize( $this->session->user );
			static::$acl = unserialize( $this->session->acl );
		}
		// иначе делаем его "гостем"
		else {
			if ( isset($this->session->acl_guest) )
				static::$acl = unserialize( $this->session->acl_guest );
			else{
				static::$acl = new ACL_Guest();
				$this->session->acl_guest = serialize( static::$acl );
			}
		}
	}
	
	
	public function logout(){
		if ( static::$logged ){
			unset( $this->session->user, $this->session->acl, $this->acl_guest );
			static::$logged = false;
		}
	}
	
	
	public function login( $login, $pass ){
		$user = new Model\User;
		$user->get_by_login_password( $login, $pass );
		if ( $user->exists() ){
			static::$logged = true;
			static::$user = $user;
			static::$acl = new ACL( $user->id );
			$this->session->user = serialize( static::$user );
			$this->session->acl = serialize( static::$acl );
			unset( $this->session->acl_guest );
		}
		return static::$logged;
	}
	
	
	public static function is_logged(){
		return static::$logged;
	}
	
	
	public static function get_user(){
		return static::$user;
	}
}
?>