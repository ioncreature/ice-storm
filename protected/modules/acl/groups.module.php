<?php
/*
	ACL
	Groups permissions
	2011
	Kurmashev Rinat, Marenin Alex
*/
$r = \Request\Parser::get_instance();
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
$groups = $db->query("SELECT * FROM auth_groups");

// список разрешений
$perms = $db->query("SELECT * FROM auth_permissions");
$i = 0;
//
// ВЫВОД
//
Template::top();
?>

<h2>Права доступа групп</h2><br/>

<table class="common">
	<tr>
		<th>Разрешение\Группа</th>
		<?php foreach ( $groups as $g ): ?>
			<th title="<?= $g['description'] ?>"><?= $g['name'] ?></th>
		<?php endforeach; ?>
	</tr>

	<?php foreach ( $perms as $p ):
		$pgs = $db->query("
			SELECT permission_id, group_id, type
			FROM auth_group_permissions
			WHERE permission_id = '{$p['id']}'
		");
		$pg = array();
		foreach ( $pgs as $g_p )
			$pg[$g_p['group_id']] = $g_p['type'];
	?>
		<tr <?= $i++ % 2 === 1 ? 'class="odd"' : ''?>>
			<td class="left"><?= $p['description'] ?></td>
			<?php foreach ( $groups as $g ): ?>
				<td>
					<input
						type="checkbox"
						<?= isset( $pg[$g['id']] ) ? 'checked="checked"' : '' ?>
						value="<?= $p['id'] .','. $g['id'] ?>"
					/><br>
				</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
</table>


<script type="text/javascript">
	$(document).ready( function(){
		$("input:checkbox").click( function(){
			var arr = $( this ).val().split( ',' );
			$.post( '<?= WEBURL .'acl/groups' ?>', {
				permission_id: arr[0],
				group_id: arr[1],
				stat: $( this ).is(':checked') ? "allow" : "deny"
			});
		});
	});
</script>

<?= Template::bottom() ?>