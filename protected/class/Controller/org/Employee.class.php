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
			'' => 'redirect',
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


	public function redirect( $path = null ){
		redirect( WEBURL . $path );
	}


	public function show_employee( $id ){
		$employee = new \Model\Employee( $id );
		$this->view->set_template( 'page/employee_show' );
		return array(
			'personal'	 => $employee->Human->export_array(),
			'employee'   => $employee->export_array(),
			'department' => $employee->Department->export_array(),
			'edit'       => \Auth::$acl->employee_edit ? WEBURL . $this->get_controller_path() . $id .'/edit' : false
		);
	}


	public function show_employee_added( $id ){
		$res = $this->show_employee( $id );
		$res['successfully_added'] = true;
		return $res;
	}


	public function show_add_employee(){
		$employee = new \Model\Employee();
		return array(
			'employee'   => $employee,
			'human'      => $employee->Human,
			'department' => $employee->Department,
			'form'       => new \Form\Employee( $this->get_controller_path(), 'POST' )
		);
	}


	public function add_employee(){
		$r = $this->request->get_all();
		$employee = new \Model\Employee();
		$db = \Fabric::get( 'db' );
		if ( $this->validate($r) !== true )
			// TODO: сделать нормальный вывод во вью
			return false;

		$form = new \Form\Employee( WEBURL . $this->get_controller_path(), 'POST', array(), $employee );

		try {
			$db->start();

			// add new human
			if ( isset($r['human_source']) and $r['human_source'] === 'new' ){
				unset( $r['human_id'] );
				$employee->Human
					->apply( $employee->Human->filter($r, 'human_') )
					->save();
			}
			else
				$employee->Human->get_by_id( $r['human_id'] );

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

		// TODO: придумать красивый способ для редиректа (мб через \Response\AbstractResponse)
		redirect( WEBURL . $this->get_controller_path() . $employee->id .'/success' );
	}


	public function edit_employee( $id ){
		$out = $this->show_edit_employee( $id );
		$form = $out['form'];
		$employee = $out['employee'];

		$form->fetch( $this->request->export_array() );
		if ( $form->validate() ){
			$employee->apply( $employee->filter($form->export_array()) );
			$employee->save();
			redirect( WEBURL . $this->get_controller_path() . $employee->id .'/success' );
		}
		else
			return $out;
	}


	public function show_edit_employee( $id ){
		$employee = new \Model\Employee( $id );
		$form = new \Form\Employee( WEBURL . $this->get_controller_path() . $id, 'POST' );
		$form->fetch( $employee->export_array() );
		return array(
			'edit'          => true,
			'employee'      => $employee,
			'human'         => $employee->Human,
			'department'    => $employee->Department,
			'personal_data' => $employee->Human->export_array(),
			'form'			=> $form
		);
	}


	/**
	 * @param array $r
	 * @return string|true
	 */
	public function validate( array &$r ){
		if ( isset($r['post'], $r['department_id'], $r['adoption_date'], $r['work_rate']) ){
			if ( strtotime($r['adoption_date']) === -1 or strtotime($r['adoption_date']) > time() )
				return 'Incorrect adoption date';

			$r['adoption_date'] = date( 'Y-m-d', strtotime($r['adoption_date']) );
		}
		else
			return 'Required fields are not received';
		return true;
	}
}
