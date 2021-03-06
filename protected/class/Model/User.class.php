<?php
/**
 * Модель для работы с данными пользователя
 * @author Marenin Alex
 * 2011
 */

namespace Model;

class User extends AbstractModel{
	
	protected static
		$table = 'auth_users',
		$primary_key = 'id',
		$fields = array(
			'id', 'login', 'password', 'email',
			'active' => 'yes',
			'human_id' => array(
				'type' => 'int',
				'default' => 0,
				'foreign_key' => 'id',
				'model' => '\Model\Human',
			)
		);

	
	public function get_by_login_password( $login, $password ){
		$this->db_connect();
		$login = $this->db->safe( $login );
		$password =  $this->hash( $password );
		$user = $this->db->fetch_query("
			SELECT *
			FROM ". static::$table ."
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


	public function get_users(){
		$this->db_connect();
		return $this->db->query("
			SELECT
				auth_users.id,
				auth_users.login,
				org_humans.full_name
			FROM
				auth_users
				LEFT JOIN org_humans ON org_humans.id = auth_users.human_id
		");
	}
}
?>