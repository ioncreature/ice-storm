<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

namespace Controller\org;

class Staff extends \Controller\AbstractController {

	public $routes = array(
		'GET' => array(
			'' => 'show_list'
		)
	);


	public function show_list(){
		$this->view->set_template( 'page/staff' );
	}
}
