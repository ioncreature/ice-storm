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
			'::int' => 'show_student'
		),
		'POST' => array(
			'' => array(
				'method' => 'add_student',
				'permission' => 'student_edit'
			)
		)
	);


	public function show_students(){
		$this->view->set_template( 'page/students' );
		return array(
			'new_student' => $this->show_new_student_form()
		);
	}


	public function show_new_student_form(){
		$student = new \Model\Student();

		$form = new \Form\Student( $this->get_full_path(), 'POST' );
		$form->fetch( $student->export_array() );

		$human_form = new \Form\Human( $this->get_full_path() );
		$human_form->fetch( $student->Human->export_array() );

		return array(
			'form'       => $form,
			'employee'   => $student,
			'human_form' => $human_form,
			'action'     => 'add',
		);
	}


	public function add_student(){
	}


	public function show_student( $id ){
	}

}
