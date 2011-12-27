<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

/**
 * Application provides base MVC functionality
 */
class Application {

	protected $request = null;
	protected $response = null;


	public function __construct( \Request\Parser $r, array $config ){
	}

}
