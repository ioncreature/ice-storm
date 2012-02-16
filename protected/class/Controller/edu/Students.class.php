<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\edu;
use \Controller\AbstractController as Controller;

class Students extends Controller {

	public $routes = array(
		'GET' => array(
			'' => 'show_students',
			'new' => array(
				'method' => 'show_new_student_form',
				'permission' => 'employee_add',
				'view' => '\View\Html'
			)
		)
	);


	public function show_students(){
		$this->view->set_template( 'page/students' );
	}


	public function show_new_student_form(){
		$this->view->set_template( 'form/student' );

		$student = new \Model\Employee( $id );
		$form = new \Form\Employee( WEBURL . $this->get_path(), 'POST' );
		$form->fetch( $student->export_array() );
		return array(
			'form'     => $form,
			'employee' => $student,
			'human'    => $student->Human,
			'group'    => $student->Department,
			'action'   => 'add'
		);
	}

}
