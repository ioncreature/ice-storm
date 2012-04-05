<?php
/**
 * @author Marenin Alex
 *         April 2012
 */

namespace Auth;

class User {

	/**
	 * @var \Acl\User
	 */
	protected $acl;

	/**
	 * @var \Model\User
	 */
	protected $model;

	/**
	 * @var int|string
	 */
	protected $id;

	/**
	 * @var \Session\Container
	 */
	protected $session;


	protected $authenticated = false;


	public function __construct(){
		$this->session = \Ice::registry('session')->get_container( 'AuthUser' );
	}


	public function __sleep(){
		return array( 'acl', 'model', 'id', 'authenticated' );
	}


	/**
	 * @return \Acl\User
	 */
	public function get_acl(){
		return $this->acl;
	}


	public function get_model(){
		if ( !$this->model and $this->id )
			$this->model = new \Model\User( $this->id );
		return $this->model;
	}


	/**
	 * Метод для авторизации
	 * @param IUserIdentity $identity
	 * @throws \Exception\Base
	 */
	public function login( IUserIdentity $identity ){
		if ( $this->authenticated )
			throw new \Exception\Base( 'User already authenticated' );

		$identity->authenticate();
		if ( $identity->is_authenticated() ){
			$this->id = $identity->get_id();

			// сохраняем в сессию
			$this->session->user = serialize( $this );
		}
	}


	public function logout(){}

}
