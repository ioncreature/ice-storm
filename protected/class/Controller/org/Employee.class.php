<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\org;
use \Controller\AbstractController as Controller;

class Employee extends Controller {
	public $routes = array(
		'GET' => array(
			'' => 'show_list',
			'new' => 'show_add_employee',
			'::int' => 'show_employee',
			'::int/success' => 'show_employee_added',
	 		'::int/edit' => array(
				'permission' => 'employee_edit',
				'method' => 'show_edit_employee'
			)
		),
		'POST' => array(
			'' => array(
				'permission' => 'employee_add',
				'method' => 'add_employee'
			),
			'::int' => array(
				'permission' => 'employee_edit',
				'method' => 'edit_employee'
			)
		),
		'DELETE' => array(
			'::int' => array(
				'permission' => 'employee_delete',
				'method' => 'delete_employee'
			)
		)
	);


	public function init(){
		$this->view = new \View\WebPage();
		$this->view->set_layout( 'layout/base' );
		$this->view->set_template( 'page/employee_edit' );
	}


	/**
	 * Показывает страницу с данными сотрудника
	 * TODO: нарисовать красивый диз этой страницы, сделать его типовым
	 * @param int $id
	 * @return array
	 */
	public function show_employee( $id ){
		$employee = new \Model\Employee( $id );
		$this->view->set_template( 'page/employee_show' );
		return array(
			'personal'	 => $employee->Human->export_array(),
			'employee'   => $employee->export_array(),
			'department' => $employee->Department->export_array(),
			'edit'       => \Auth::$acl->employee_edit ? $this->get_full_path() . $id .'/edit' : false,
		);
	}


	public function show_employee_added( $id ){
		$res = $this->show_employee( $id );
		$res['successfully_added'] = true;
		return $res;
	}


	/**
	 * Показывает форму добавления нового сотрудника
	 * @return array
	 */
	public function show_add_employee(){
		$employee = new \Model\Employee();
		return array(
			'employee'   => $employee,
			'human'      => $employee->Human,
			'department' => $employee->Department,
			'form'       => new \Form\Employee( $this->get_full_path(), 'POST' ),
			'human_form' => new \Form\Human( $this->get_full_path(), 'POST' ),
			'action'     => 'add',
		);
	}


	/**
	 * Добавление сотрудника
	 * @return array
	 * @throws \Exception\SQL|\Exception\Validate
	 */
	public function add_employee(){
		$r = $this->request->export_array();
		$employee = new \Model\Employee();
		$db = \Db\Factory::get( 'db' );
		// форма сотрудника
		$form = new \Form\Employee( $this->get_full_path(), 'POST', array(), $employee );
		$form->fetch( $r );
		// подформа персональных данных
		$human_form = new \Form\Human( $this->get_full_path(), 'POST', array(), $employee->Human );
		$human_form->fetch( $r );

		try {
			if ( !$form->validate() )
				throw new \Exception\Validate( 'Shit' );

			$db->start();

			// add new human
			if ( isset($r['human_source']) and $r['human_source'] === 'new' ){
				if ( $human_form->validate() ){
					unset( $r['human_id'] );
					$employee->Human->apply( $employee->Human->filter($r, 'human_') )->save();
				}
				else
					throw new \Exception\Validate( 'Sux' );
			}
			else
				$employee->Human->get_by_id( $r['human_id'] );

			// add new employee
			if ( $employee->Human->exists() ){
				$data = $employee->filter( $r );
				$employee->apply( $data );
				$employee->human_id = $employee->Human->id;
				$employee->save();
			}
			else
				throw new \Exception\SQL( 'Human for employee not defined' );

			$db->commit();
		}
		catch ( \Exception\SQL $e ){
			$db->rollback();
			$this->set_status( \Response\AbstractResponse::STATUS_ERROR );
			return array( 'msg' => $e->getMessage() );
		}

		$this->redirect( $this->get_full_path() . $employee->id .'/success' );
	}


	/**
	 * Редактирование данных сотрудника
	 * @param $id
	 * @return array
	 */
	public function edit_employee( $id ){
		$out = $this->show_edit_employee( $id );
		$form = $out['form'];
		/** @var \Model\Employee */
		$employee = $out['employee'];

		$form->fetch( $this->request->export_array() );
		if ( $form->validate() ){
			$employee->apply( $employee->filter($form->export_array()) );
			$employee->save();
			$this->redirect( $this->get_full_path() . $employee->id .'/success' );
		}
		else
			return $out;
	}


	/**
	 * Показывает форму редактирования сотрудника
	 * @param $id
	 * @return array
	 */
	public function show_edit_employee( $id ){
		$employee = new \Model\Employee( $id );
		$form = new \Form\Employee( $this->get_full_path() . $id, 'POST' );
		$form->fetch( $employee->export_array() );
		return array(
			'action'     => 'edit',
			'employee'   => $employee,
			'human'      => $employee->Human,
			'department' => $employee->Department,
			'form'		 => $form,
		);
	}


	public function show_list(){
		$this->view->set_template( 'page/staff' );
		return array( 'can_add' => \Auth::$acl->employee_add );
	}
}
