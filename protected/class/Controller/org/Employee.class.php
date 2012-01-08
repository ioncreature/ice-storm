<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\org;
use \Controller\AbstractController as Controller;

class Employee extends Controller {
	public $routes = array(
		'get' => array(
			'' => 'redirect',
			'new' => 'show_add_employee',
			'::int' => 'show_employee',
			'::int/edit' => array(
				'permission' => 'employee_edit',
				'method' => 'show_edit_employee'
			)
		),
		'post' => array(
			'add' => array(
				'permission' => 'employee_add',
				'method' => 'add_employee'
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
			'personal'	=> $employee->Human->export_array(),
			'employee'  => $employee->export_array()
		);
	}


	public function show_add_employee(){
		$employee = new \Model\Employee();
		return array(
			'employee'   => $employee,
			'human'      => $employee->Human,
			'department' => $employee->Department
		);
	}


	public function add_employee(){
		$r = $this->request->get_all();
		$employee = new \Model\Employee();
		$db = \Fabric::get( 'db' );

		try {
			$db->start();

			$this->validate( $r );

			// add new human
			if ( isset($r['human_source']) and $r['human_source'] === 'new' ){
				unset( $r['human_id'] );
				$employee->Human
					->apply( $this->employee->Human->filter($r, 'human_') )
					->save();
			}
			else
				$employee->Human->get_by_id( $r['human_id'] );

			if ( $employee->Human->exists() ){
				$data = $employee->filter( $r );
				$data['adoption_date'] = date( 'Y-m-d', strtotime($data['adoption_date']) );
				$employee->apply( $data );
				$employee->human_id = $employee->Human->id;
				$employee->save();
				var_dump($data, $employee->export_array(), $employee->exists(), $employee->Human->export_array() );
			}
			else
				throw new \Exception\Validate( 'Human for employee not defined' );

			$db->commit();
		}
		catch ( \Exception\AbstractException $e ){
			$db->rollback();
			$this->set_status( \Response\AbstractResponse::STATUS_FORBIDDEN );
			return array( 'msg' => $e->getMessage() );
		}

		$res = $this->show_employee( $employee->id );
		$res['successfully_added'] = true;
		return $res;
	}


	public function show_edit_employee( $id ){
		$employee = new \Model\Employee( $id );
		return array(
			'employee'      => $employee,
			'human'         => $employee->Human,
			'department'    => $employee->Department,
			'personal_data' => $employee->Human->exportArray()
		);
	}


	/**
	 * @param array $r
	 * @throws \Exception\Validate
	 */
	public function validate( array $r ){
		if ( isset($r['post'], $r['department_id'], $r['adoption_date'], $r['work_rate']) ){

			if ( strtotime($r['adoption_date']) === -1 or strtotime($r['adoption_date']) > time() )
				throw new \Exception\Validate( 'Incorrect adoption date' );
		}
		else
			throw new \Exception\Validate( 'Required fields are not received' );
	}
}
