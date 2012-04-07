<?php
/**
 * @author Marenin Alex
 *         April 2012
 */

namespace Auth;

abstract class DbUserIdentity implements \Auth\IUserIdentity {

	/**
	 * @var string
	 */
	protected $login;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var int|string
	 */
	protected $id;

	/**
	 * @var bool
	 */
	protected $authenticated = false;



	/**
	 * @param string $login
	 * @param string $pass
	 */
	public function __construct( $login, $password ){
		$this->login = $login;
		$this->password = $password;
	}

	/**
	 * @return bool
	 */
	public function authenticate(){
		$user_model = new \Model\User();
		$user_model->get_by_login_password( $this->login, $this->password );

		if ( $user_model->exists() ){
			$this->authenticated = true;
			$this->id = $user_model->id;
			return true;
		}
		return false;
	}


	public function __get( $key ){
		return isset($this->identity_data[$key]) ? $this->identity_data[$key] : null;
	}


	public function get_id(){
		return $this->id;
	}


	public function is_authenticated(){
		return $this->authenticated;
	}
}
