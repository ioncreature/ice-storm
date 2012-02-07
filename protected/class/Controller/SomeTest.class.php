<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller;

use \Controller\AbstractController as Controller;

class SomeTest extends Controller {

	protected $routes = array(
		'GET' => array(
			'' => 'run_page',
			'ololo' => 'run_ololo',
			'redirect' => 'run_redirect',
			'test' => 'run_test'
		)
	);


	public function __destruct(){
		echo $this->get_status();
	}

	public function init(){
		$this->view = new \View\WebPage();
		$this->view->set_template( 'page/some_test' );
	}


	public function run_page(){
		$this->view->add( 'some_key', 'RUN_PAGE' );
	}

	public function run_test(){
		$this->view = new \View\Json();
		return array( 'some_key' => 'Sweety!' );
	}

	public function run_ololo(){
		$this->set_status( \Response\AbstractResponse::STATUS_FORBIDDEN );
	}

	public function run_redirect(){
		$this->redirect( WEBURL . 'some_test' );
	}
}
