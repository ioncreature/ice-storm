<?php
$r = RequestParser::get_instance();
$db = Fabric::get('db');
$groups = $db->query("
	SELECT * 
	FROM
		auth_groups
");
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
				<td>true</td>
			<?php else: ?>
				<td>false</td>
			<?php endif; ?>	
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
</table>



<?= bottom() ?>