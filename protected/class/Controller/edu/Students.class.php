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
			'' => array(
				'method' => 'show_students',
				'permission' => 'student_read'
			),
			'::int' => 'show_student',
			'new' => 'show_add_student'
		),
		'POST' => array(
			'new' => array(
				'method' => 'add_student',
				'permission' => 'student_edit'
			),
			'::int' => array(
				'method' => 'add_student',
				'permission' => 'student_edit'
			)
		)
	);


	public function show_students(){
		$this->view->set_template( 'page/students' );
		return array(
			'can_add' => \Auth::$acl->student_edit
		);
	}


	public function show_add_student(){
		$this->view->set_template( 'page/student_edit' );
		$student = new \Model\Student();

		$form = new \Form\Student( $this->get_full_path() .'new', 'POST' );
		$form->fetch( $student->export_array() );

		$human_form = new \Form\Human( $this->get_full_path() );
		$human_form->fetch( $student->Human->export_array() );

		return array(
			'form'       => $form,
			'student'    => $student,
			'human_form' => $human_form,
			'action'     => 'add',
		);
	}


	public function add_student(){
		$r = $this->request->export_array();
		$student = new \Model\Student();
		$db = \Db\Fabric::get( 'db' );
		// форма студента
		$form = new \Form\Student( $this->get_full_path() .'new', 'POST', array(), $student );
		$form->fetch( $r );
		// подформа персональных данных
		$human_form = new \Form\Human( $this->get_full_path() .'new', 'POST', array(), $student->Human );
		$human_form->fetch( $r );

		try {
			if ( !$form->validate() )
				throw new \Exception\Validate( 'Shit' );

			$db->start();

			// add new human
			if ( isset($r['human_source']) and $r['human_source'] === 'new' ){
				if ( $human_form->validate() ){
					unset( $r['human_id'] );
					$student->Human->apply( $student->Human->filter($r, 'human_') )->save();
				}
				else
					throw new \Exception\Validate( 'Sux' );
			}
			else
				$student->Human->get_by_id( $r['human_id'] );

			// add new student
			if ( $student->Human->exists() ){
				$data = $student->filter( $r );
				$student->apply( $data );
				$student->human_id = $student->Human->id;
				$student->save();
			}
			else
				throw new \Exception\SQL( 'Human for student not defined' );

			$db->commit();
		}
		catch ( \Exception\SQL $e ){
			$db->rollback();
			$this->set_status( \Response\AbstractResponse::STATUS_ERROR );
			return array( 'msg' => $e->getMessage() );
		}
		catch ( \Exception\Validate $e ){
			// on error - show current page with error messages
			$db->rollback();
			$this->view->set_template( 'page/student_edit' );
			return array(
				'form'       => $form,
				'student'    => $student,
				'human_form' => $human_form,
				'action'     => 'add',
			);
		}

		// on success - redirect
		$this->redirect( $this->get_full_path() . $student->id .'/success' );
	}


	public function show_student( $id ){
		$this->view->set_template( 'page/student_show' );
	}
}
