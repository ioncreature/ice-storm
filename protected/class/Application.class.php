<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

/**
 * Single application entry point
 */
class Application {

	protected $request;

	protected $response;

	protected $user;

	protected $session;

	public function __construct( \Request\Parser $r, array $config ){
	}

	public function __destruct(){
	}

}
