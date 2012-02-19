<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

/**
 * Single application entry point
 */
class SpviApp extends \Base\AbstractApplication {

	/**
	 * @var \Request\Pasrer
	 */
	public static $request;

	/**
	 * @var \Response\AbstractResponse
	 */
	public static $response;


	protected $user;


	protected $session;


	public function __construct( \Request\Parser $r, array $config ){
	}


	public function __destruct(){
	}


	public function get_user(){

	}
}
