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
			'::int' => 'show_employee',
			'new' => 'show_add_employee'
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
		$this->view->set_template( 'page/employee' );
	}


	public function redirect(){
		redirect( WEBURL );
	}


	public function show_employee( $id ){
		$employee = new \Model\Employee( $id );
		return array(
			'employee'      => $employee,
			'human'         => $employee->Human,
			'department'    => $employee->Department,
			'personal_data' => $employee->Human->exportArray()
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
		return array(
			'fuck' => 'off'
		);
	}

}
