<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\edu;

class Teachers extends \Controller\AbstractController {

	public $routes = array(
		'GET' => array(
			'' => 'show_list',
			'::int' => 'show_teacher'
		)
	);

	public function init(){

	}

	public function show_list(){
		$this->view->set_template( 'page/human_list' );
		return array( 'type' => 'teacher' );
	}
}
