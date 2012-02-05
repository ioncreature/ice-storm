<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Controller\acl;
use \Controller\AbstractController as Controller;

class Groups extends Controller {

	protected $routes = array(
		'GET' => array(
			'' => array(
				'method' => 'show',
				'permission' => 'acl_read',
			),
			'::int' => array(
				'method' => 'show',
				'permission' => 'acl_read',
			)
		),
		'POST' => array(
			'' => array(
				'method' => 'edit',
				'permission' => 'acl_edit',
				'view' => '\View\Json'
			)
		),
		'DELETE' => array()
	);


	protected $db = null;

	public function init(){
		$this->db = \Fabric::get( 'db' );
	}


	public function show( $group_id = null ){
		$group_id = (int) $group_id;
		$groups = $this->db->query("
			SELECT
				auth_groups.id,
				auth_groups.name,
				auth_groups.description
			FROM
				auth_groups
			". ($group_id > 0 ? "WHERE auth_groups.id = '$group_id'" : '') ."
			ORDER BY auth_groups.name
			LIMIT 8
		", "id" );
		$permissions = $this->db->query( "SELECT id, name, description FROM auth_permissions", "id" );

		if ( !$groups ){
			$this->set_status( \Response\AbstractResponse::STATUS_NOT_FOUND );
			return false;
		}

		$in = implode( ',', array_keys($groups) );
		foreach ( $permissions as $id => &$p )
			$p['subjects'] = $this->db->query("
				SELECT
					permission_id,
					group_id as id,
					type
				FROM auth_group_permissions
				WHERE
					group_id IN ($in) AND
					permission_id = '$id'
			", 'id' );

		$this->view->set_template( 'page/acl' );
		return array(
			'subjects' => $groups,
			'permissions' => $permissions,
			'path' => $this->get_path(),
			'type' => 'groups'
		);
	}


	/**
	 * Edit group rights
	 * @return array
	 */
	public function edit(){
		$r = $this->request;
		$db = $this->db;

		if ( isset($r->permission_id, $r->subject_id, $r->type) ){
			$permission_id = (int) $r->permission_id;
			$subject_id = (int) $r->subject_id;
			$type = mb_strtolower($r->type) === 'allow' ? 'allow' : 'deny';

			if ( mb_strtolower($r->type) === 'allow' ){
				// проверка
				$perm = $db->fetch_query("
					SELECT *
					FROM auth_group_permissions
					WHERE
						group_id = '$subject_id' and
						permission_id = '$permission_id'
					LIMIT 1
				");
				if ( !$perm )
					$db->insert( 'auth_group_permissions', array(
						'group_id' => $subject_id,
						'permission_id' => $permission_id,
						'type' => 'allow'
					));
			}
			else {
				$db->query("
					DELETE
					FROM auth_group_permissions
					WHERE
						group_id = '$subject_id' and
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