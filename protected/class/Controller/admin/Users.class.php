<?php
/**
 * @author Marenin Alex
 *         March 2012
 */

namespace Controller\admin;

class Users extends \Controller\AbstractController {

	public $default = array(
		'view' => array(
			'type' => '\View\WebPage'
		)
	);

	public $routes = array(
		'GET' => array(
			'' => array(
				'method' => 'show_user_list',
				'view' => array(
					'template' => 'page/admin/users'
				)
			),
			'::int' => array(
				'method' => 'show_user_info',
				'view' => array(
					'template' => 'page/admin/user_info'
				),
			),
			'show_diff' => 'show_diff',
		),
		'POST' => array(
			'apply_diff' => array(
				'method' => 'apply_diff',
				'view' => '\View\Json'
			)
		)
	);


	public function show_user_list(){
		$model = new \Model\User();

		return array(
			'users' => $model->get_users(),
		);
	}


	public function show_user_info( $id ){
		$user = new \Model\User( $id );
		if ( !$user->exists() ){
			$this->redirect( WEBURL );
		}


		$student = new \Model\Student();
		$student->load_by_human_id( $user->id );

		return array(
			'id' => $user->id,
			'login' => $user->login,
			'full_name' => $user->Human->full_name,
			'can_edit' => true,
			'is_student' => $student->exists(),
			'student_model' => $student
		);
	}

	public function show_diff(){
		$diff = new SuperDiff();
		return array(
			'diff' => $diff->generate
		);
	}


	public function apply_diff(){
		$field = $this->request->field;
		$value = $this->request->value;
		$user_id = $this->request->user_id;

		try {
			$model = new \Model\User( $user_id );
			$model->Human->{$field} = $value;
			$model->save();
			$status = true;
		}
		catch ( \Exception $e ){
			$status = false;
		}

		return array(
			'status' => $status
		);
	}

}
