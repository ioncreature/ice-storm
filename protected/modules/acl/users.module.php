<?php
/*
	ACL 
	Users and groups
	2011
*/
$r = RequestParser::get_instance();
$db = Fabric::get('db');

if ( isset( $r->permission_id, $r->user_id, $r->stat ) ){
	$permission_id = (int) $r->permission_id;
	$user_id = (int) $r->user_id;
	$type = mb_strtolower($r->stat) === 'allow' ? 'allow' : 'deny';
	
	if ( mb_strtolower($r->stat) === 'allow' ){
		// проверка
		$perm = $db->fetch_query("
			SELECT * 
			FROM auth_user_permissions
			WHERE
				user_id = '$user_id' and
				permission_id = '$permission_id'
			LIMIT 1
		");
		
		if ( !$perm )
			$db->insert( 'auth_user_permissions', array(
				'user_id' => $user_id,
				'permission_id' => $permission_id,
				'type' => 'allow'
			));
	}
	else {
		$db->query("
			DELETE 
			FROM auth_user_permissions 
			WHERE
				user_id = '$user_id' and
				permission_id = '$permission_id'
			LIMIT 1
		");
	}
	die( json_encode( array( 'status' => true )));
}

// список пользователей
$users = $db->query("SELECT * FROM auth_users");

// список разрешений
$perms = $db->query("SELECT * FROM auth_permissions");


//
// ВЫВОД
//
Template::top();
?>

<table>
	<tr>
		<td>Пользователь\разрешение</td>
		<?php foreach ( $perms as $p ): ?>
			<td><?= $p['description'] ?></td>
		<?php endforeach; ?>
	</tr>
	<?php foreach ( $users as $u ): 
			$uss = $db->query("
				SELECT type, user_id, permission_id
				FROM auth_user_permissions
				WHERE user_id = '{$u['id']}'
			");
			$us = array();
			foreach ( $uss as $u_s ){
				$us[$u_s['permission_id']] = $u_s['type'];
			}
	?>
	<tr>	
		<td><?= $u['login'] ?></td>
		<?php foreach ( $perms as $p ): ?>
			<?php if ( isset($us[$p['id']]) ): ?>
				<td><input type="checkbox" name="gp_checkbox" checked="checked" value="<?= $p['id'] .','. $u['id'] ?>"/><br></td>
			<?php else: ?>
				<td><input type="checkbox" name="gp_checkbox" value="<?= $p['id'] .','. $u['id'] ?>"/><br></td>
			<?php endif; ?>	
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
</table>

<script type="text/javascript">
	$(document).ready( function(){
		$("input:checkbox").click( function(){
			var index = $("input:checkbox").index(this);
			var arr = ( $(this).val().split(',') );
			var url = '<?= WEBURL .'acl/users' ?>';
			if ( $(this).is(':checked') )
				$.post( url, { permission_id: arr[0], user_id: arr[1], stat: "allow" } );
			else
				$.post( url, { permission_id: arr[0], user_id: arr[1], stat: "deny" } );
		});	
	});
</script>

<?= Template::bottom() ?>