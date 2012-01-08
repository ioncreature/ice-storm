<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\acl;
use \Controller\AbstractController as Controller;

class Users extends Controller {

	protected $routes = array(
		'get' => array(
			'' => array(
				'method' => 'show',
				'permission' => 'acl_read',
			),
			'::int' => array(
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


	public function show( $user_id = null ){
		$user_id = (int) $user_id;
		$users = $this->db->query("
			SELECT
				auth_users.id,
				auth_users.login as description,
				org_humans.full_name as name
			FROM
				auth_users
				LEFT JOIN org_humans ON auth_users.human_id = org_humans.id
			". ($user_id > 0 ? "WHERE auth_users.id = '$user_id'" : '') ."
			ORDER BY org_humans.full_name
			LIMIT 8
		", "id" );
		$permissions = $this->db->query( "SELECT id, name, description FROM auth_permissions", "id" );

		$in = implode( ',', array_keys($users) );
		foreach ( $permissions as $id => &$p )
			$p['subjects'] = $this->db->query("
				SELECT
					permission_id,
					user_id as id,
					type
				FROM auth_user_permissions
				WHERE
					user_id IN ($in) AND
					permission_id = '$id'
			", 'id' );

		$this->view->set_template( 'page/acl' );
		return array(
			'subjects' => $users,
			'permissions' => $permissions,
			'path' => $this->get_controller_path(),
			'type' => 'users'
		);
	}


	/**
	 * Edit user rights
	 * @return array
	 */
	public function edit(){
		$r = $this->request;
		$db = $this->db;

		if ( isset( $r->permission_id, $r->subject_id, $r->type ) ){
			$permission_id = (int) $r->permission_id;
			$subject_id = (int) $r->subject_id;
			$type = mb_strtolower($r->type) === 'allow' ? 'allow' : 'deny';

			if ( mb_strtolower($r->type) === 'allow' ){
				// проверка
				$perm = $db->fetch_query("
					SELECT *
					FROM auth_user_permissions
					WHERE
						user_id = '$subject_id' and
						permission_id = '$permission_id'
					LIMIT 1
				");
				if ( !$perm )
					$db->insert( 'auth_user_permissions', array(
						'user_id' => $subject_id,
						'permission_id' => $permission_id,
						'type' => 'allow'
					));
			}
			else {
				$db->query("
					DELETE
					FROM auth_user_permissions
					WHERE
						user_id = '$subject_id' and
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

