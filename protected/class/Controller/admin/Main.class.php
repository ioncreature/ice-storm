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
			),
			'someaction' => 'some_method',
			'someajaxaction' => array(
				'method' => 'ajax_action',
				'view' => '\View\Json'
			),
		)
	);


	public function show(){
		$this->view->set_template( 'page/admin/main' );

		return array(
			'test' => 'man!',
		);
	}

}
