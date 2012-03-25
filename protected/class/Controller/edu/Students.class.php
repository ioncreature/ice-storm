<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

namespace Controller\edu;
use \Controller\AbstractController as Controller;

class Students extends Controller {

	public $default = array(
		'permission' => 'student_read',
		'view' => array(
			'type' => '\View\WebPage',
			'layout' => 'layout/base',
			'template' => 'page/students'
		),
	);

	public $routes = array(
		'GET' => array(
			'' => 'show_students',
			'::int/edit' => array(
				'method' => 'show_edit_student',
				'view' => array( 'template' => 'page/student_edit' )
			),
			'::int' => 'show_student',
			'::int/success' => 'show_student',
			'new' => 'show_add_student',
			'group/::int' => array(
				'method' => 'get_group_students',
				'view' => '\View\Json'
			),
			'group' => array(
				'method' => 'get_all_students',
				'view' => '\View\Json'
			),
			'search' => array(
				'method' => 'search_students',
				'view' => '\View\Json',
				'query' => array( 'name' ),
			)
		),
		'POST' => array(
			'new' => array(
				'method' => 'add_student',
				'permission' => 'student_edit'
			),
			'::int' => array(
				'method' => 'edit_student',
				'permission' => 'student_edit'
			)
		)
	);


	public function show_students(){
		return array( 'can_add' => \Auth::$acl->student_edit );
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
		$db = \Db\Factory::get( 'db' );
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
		$student = new \Model\Student( $id );
		if ( !$student->exists() )
			$this->set_status( \Response\AbstractResponse::STATUS_NOT_FOUND );
		else
			return array(
				'student' => $student,
				'edit' => \Auth::$acl->student_edit
			);
	}


	/**
	 * Редактирование студента
	 * @param int $id
	 * @return bool
	 */
	public function edit_student( $id ){
		$student = new \Model\Student( $id );
		if ( !$student->exists() ){
			$this->set_status( \Response\AbstractResponse::STATUS_NOT_FOUND );
			return false;
		}

		$form = new \Form\Student( $this->get_full_path() . $id, 'POST', array(), $student );
		$form->fetch( $this->request->get_http_post() );

		// валидируем и сохраняем
		if ( $form->validate() ){
			$student->apply( $form->export_array() );
			$student->save();
			$this->redirect( $this->get_full_path() . $id );
			return true;
		}

		// если форма не прошла валидацию, то показываем форму
		return $this->show_edit_student( $id, $form, $student );
	}


	/**
	 * Показать форму редактирвания студента
	 * @param int $id
	 * @param null|\Form\AbstractForm $form
	 * @param null|\Form\AbstractForm $student
	 * @return array
	 */
	public function show_edit_student( $id, $form = null, $student = null ){
		if ( !$student ){
			$student = new \Model\Student( $id );
			if ( !$student->exists() ){
				$this->set_status( \Response\AbstractResponse::STATUS_NOT_FOUND );
				return false;
			}
		}

		if ( !$form )
			$form = new \Form\Student( $this->get_full_path() . $id, 'POST', array(), $student );

		$this->view->set_template( 'page/student_edit' );
		return array(
			'form'       => $form,
			'student'    => $student,
			'action'     => 'edit',
		);
	}


	public function get_group_students( $group_id ){
		$student = new \Model\Student();
		return $this->filter_students_array( $student->get_group_students($group_id) );
	}


	public function get_all_students(){
		$student = new \Model\Student();
		return $this->filter_students_array( $student->get_all_students() );
	}


	public function search_students(){
		$name = $this->request->name;
		$student = new \Model\Student();
		return $this->filter_students_array( $student->search_by_name($name) );
	}


	protected function filter_students_array( $students ){
		$ka = array_fill_keys( array(
			'full_name', 'group_id',
			'birth_date', 'enrollment_date', 'enrollment_year',
			'dismissal_date', 'id', 'group_name'
		), true );
		return array_map( function( $s ) use( $ka ){
			$s['enrollment_year'] = date( 'Y', strtotime($s['enrollment_date']) );
			return array_intersect_key( $s, $ka );
		}, $students );
	}
}
