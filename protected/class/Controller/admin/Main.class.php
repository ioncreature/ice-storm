<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\admin;
use \Controller\AbstractController as AbstractController;

class Main extends AbstractController {

	protected $routes = array(
		'GET' => array(
			'' => array(
				'method' => 'show',
//				'permission' => 'admin_show_main'
			)
		)
	);


	public function show(){
		$this->view->set_template( 'page/admin/main' );
		$this->set_status( \Response\AbstractResponse::STATUS_FORBIDDEN );

		return array(
			'test' => 'man!',
		);
	}

}
