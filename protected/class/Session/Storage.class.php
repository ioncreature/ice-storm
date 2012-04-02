<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

namespace Session;

class Storage {

	/** @var string Session id */
	protected $sid;

	protected $started;

	/** @var \Session\Contaier[] */
	protected $containers = array();


	public function __construct( $name ){
		$this->set_name( $name );
	}


	public function set_name( $name ){
		session_name( $name );
	}


	/**
	 * @param string? $sid
	 */
	public function start( $sid = '' ){
		if ( !$sid ){
			session_id( $sid );
			$this->sid = $sid;
		}

		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		if ( !ini_get('session.auto_start') ) {
			ini_set('session.use_cookies', 1);
			session_name("s");
			session_start();
		}
		$this->started = true;
	}


	/**
	 * Returns session container
	 * @param string $name
	 * @return \Session\Container
	 */
	public function get_container( $name ){
		if ( isset($this->containers[$name]) )
			return $this->containers[$name];

		return new Container( $name );
	}


	public function close(){
		$this->started = false;
		session_write_close();
	}



	public function get_id(){
		return $this->sid;
	}
}