<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller;

use \Controller\AbstractController as Controller;

class SomeTest extends Controller {

	protected $routes = array(
		'get' => array(
			'' => 'run_page',
			'ololo' => 'run_ololo',
			'test' => 'run_test'
		)
	);


	public function __destruct(){
		echo $this->get_status();
	}

	public function init(){
		$this->view = new \View\WebPage();
		$this->view->set_template( 'page/some_test' );
//		echo $this->callback . '<br />';
//		echo $this->get_status() . '<br />';
//		$this->set_status( \Response\AbstractResponse::STATUS_FORBIDDEN );
	}


	public function run_page(){
		$this->view->add( 'some_key', 'RUN_PAGE' );
	}

	public function run_test(){
		$this->view = new \View\Json();
		$this->view->add( 'some_key', 'Sweety!' );
	}

	public function run_ololo(){
		$this->set_status( \Response\AbstractResponse::STATUS_FORBIDDEN );
	}
}
