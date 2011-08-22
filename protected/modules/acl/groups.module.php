<?php
$r = RequestParser::get_instance();
$db = Fabric::get('db');

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
	die( json_encode( array( 'status' => true )));
}

// список групп
$groups = $db->query("
	SELECT * 
	FROM
		auth_groups
");

// список разрешений
$perms = $db->query("
	SELECT * 
	FROM
		auth_permissions
");
top();
?>

<table>
	<tr>
		<td>Группа\разрешение</td>
		<?php foreach ( $perms as $p ): ?>
			<td><?= $p['description'] ?></td>
		<?php endforeach; ?>
	</tr>
	<?php foreach ( $groups as $g ): 
			$gps = $db->query("
				SELECT type, group_id, permission_id
				FROM auth_group_permissions
				WHERE group_id = '{$g['id']}'
			");
			$gp = array();
			foreach ( $gps as $g_p ){
				$gp[$g_p['permission_id']] = $g_p['type'];
			}
	?>
	<tr>	
		<td><?= $g['name'] ?></td>
		<?php foreach ( $perms as $p ): ?>
			<?php if ( isset($gp[$p['id']]) ): ?>
				<td><input type="checkbox" name="gp_checkbox" checked="checked" value="<?= $p['id'] .','. $g['id'] ?>"/><br></td>
			<?php else: ?>
				<td><input type="checkbox" name="gp_checkbox" value="<?= $p['id'] .','. $g['id'] ?>"/><br></td>
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
			var url = '<?= WEBURL .'acl/groups' ?>';
			if ( $(this).is(':checked') )
				$.post( url, { permission_id: arr[0], group_id: arr[1], stat: "allow" } );
			else
				$.post( url, { permission_id: arr[0], group_id: arr[1], stat: "deny" } );
		});	
	});
</script>

<?= bottom() ?>