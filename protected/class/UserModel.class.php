<?php
/*
	Класс для работы с данными пользователя
	Marenin Alexandr
	2011
*/

class UserModel extends Model{
	
	protected $table = 'auth_users';
	protected $fields = array('id', 'human_id', 'login', 'password', 'email', 'active');
	protected $model_name = 'User';
	protected $primary_key = 'id';
	
	public function get_by_login_password( $login, $password ){
		$this->db_connect();
		$login = $this->db->safe( $login );
		$password =  $this->hash( $password );
		$user = $this->db->fetch_query("
			SELECT * 
			FROM {$this->table} 
			WHERE 
				login = '$login' and 
				password = '$password'
			LIMIT 1
		");
		if ( $user )
			return $this->load( $user );
		else
			return false;
	}
	
	protected function before_save(){
		if ( $this->is_changed('password') )
			$this->password = $this->hash($this->data['password']);
	}
	
	public function check_password( $pass ){
		if ( isset($this->data['password']) )
			return $this->data['password'] === $this->hash($pass) ;
		else
			return 0;
	}
	
	protected function hash( $val ){
		return md5( sha1($val) .'salt' );
	}
}
?>