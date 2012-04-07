<?php
/**
 * @author Marenin Alex
 *         April 2012
 */

namespace Auth;

class User {

	/** @var \Acl\User */
	protected $acl;

	/** @var \Model\User */
	protected $model;

	/** @var int|string */
	protected $id;

	/** @var \Session\Container */
	protected $session;

	/** @var bool */
	protected $authenticated = false;

	/** @var array */
	protected $resources;


	/**
	 * @param array $resources
	 */
	public function __construct(){
		$this->session = \Ice::registry('session')->get_container( 'AuthUser' );
		$this->get_state();
		$this->resources = \Ice::config( 'component.auth.resources' );
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


	public function get_id(){
		return $this->id;
	}


	/**
	 * Метод для авторизации
	 * @param IUserIdentity $identity
	 * @throws \Exception\Base
	 */
	public function login( IUserIdentity $identity ){
		if ( isset($this->id) )
			throw new \Exception\Base( 'User already authenticated' );

		if ( $identity->authenticate() ){
			$this->id = $identity->get_id();
			$this->model = new \Model\User( $this->id );
			$this->acl = new \Acl\User( $this->id );

			// сохраняем состояние
			$this->set_state();

			// авторизуемся на доп. ресурсах
			foreach ( $this->resources as $key => $cfg ){
				/** @var \Auth\IResource $res */
				$res = new $cfg['class'];
				$this->resources[$key]['instance'] = $res;
				$res->authenticateById( $this->get_id() );
			}
		}
		return $this->is_authenticated();
	}


	public function logout(){
		if ( $this->authenticated ){
			$this->session->reset();
			unset( $this->model );
			unset( $this->acl );
			// $this->acl = new \Acl\Guest();
		}
	}


	/**
	 * Авторизован ли пользователь
	 * @return bool
	 */
	public function is_authenticated(){
		return isset( $this->id );
	}


	/**
	 * Восстанавливает состояние из сессии
	 */
	protected function get_state(){
		if ( isset($this->session->id, $this->session->model, $this->session->acl) ){
			$this->id = $this->session->id;
			$this->acl = unserialize( $this->session->acl );
			$this->model = unserialize( $this->session->model );
		}
	}


	/**
	 * Сохранение состояния в сессию
	 */
	protected function set_state(){
		$this->session->model = serialize( $this->model );
		$this->session->acl = serialize( $this->acl );
		$this->session->id = $this->id;
	}


	/**
	 * Обновление данных состояния
	 */
	public function update_state(){
		$this->set_state();
	}
}
