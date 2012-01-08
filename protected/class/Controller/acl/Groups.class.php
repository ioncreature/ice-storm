<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\acl;
use \Controller\AbstractController as Controller;

class Group extends Controller {

	protected $routes = array(
		'get' => array(
			'' => array(
				'method' => 'show',
				'permission' => 'acl_read',
			)
		),
		'post' => array(
			'' => array(
				'method' => 'edit',
				'permission' => 'acl_edit',
				'view' => '\View\Json'
			)
		),
		'delete' => array(

		)
	);


	protected $db = null;

	public function init(){
		$this->db = \Fabric::get( 'db' );
	}


	public function show(){
	}


	/**
	 * Edit user rights
	 * @return array
	 */
	public function edit(){
		$r = $this->request;
		$db = $this->db;

		if ( isset( $r->permission_id, $r->group_id, $r->stat ) ){
			$permission_id = (int) $r->permission_id;
			$group_id = (int) $r->group_id;
			$type = mb_strtolower($r->stat) === 'allow' ? 'allow' : 'deny';

			if ( mb_strtolower($r->stat) === 'allow' ){
				// проверка
				$perm = $db->fetch_query("
					SELECT *
					FROM auth_group_permissions
					WHERE
						group_id = '$group_id' and
						permission_id = '$permission_id'
					LIMIT 1
				");

				if ( !$perm )
					$db->insert( 'auth_group_permissions', array(
						'group_id' => $group_id,
						'permission_id' => $permission_id,
						'type' => 'allow'
					));
			}
			else {
				$db->query("
					DELETE
					FROM auth_group_permissions
					WHERE
						group_id = '$group_id' and
						permission_id = '$permission_id'
					LIMIT 1
				");
			}
			return array( 'status' => true );
		}
		else
			return array(
				'status' => false,
				'msg' => 'Undefined params'
			);
	}
}
