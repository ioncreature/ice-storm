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
				'permission' => 'admin_main',
				'view' => array(
					'type' => '\View\WebPage',
					'layout' => 'layout/admin',
					'template' => 'page/admin/main'
				),
			)
		)
	);


	public function show(){
		$this->view->set_template( 'page/admin/main' );
		$this->set_status( \Response\AbstractResponse::STATUS_ERROR );

		return array(
			'test' => 'man!',
		);
	}

}
