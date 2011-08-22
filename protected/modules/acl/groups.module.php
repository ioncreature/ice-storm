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
				<td><input type="checkbox" name="gp_checkbox" checked="checked" value="<?= $p['id'] .','. $g['id'] ?>"/><br></td>
			<?php else: ?>
				<td><input type="checkbox" name="gp_checkbox" value="<?= $p['id'] .','. $g['id'] ?>"/><br></td>
			<?php endif; ?>	
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
</table>

<script type="text/javascript">
  $(document).ready(function(){
	$("input:checkbox").bind('click', function(){
		var index = $("input:checkbox").index(this);
		var arr =  ($(this).val().split(','));		
		if ($(this).is(':checked')) 			
			$.post("/acl/insert", { col: arr[0], row: arr[1], stat: "allow" } );
		else 
			$.post("/acl/insert", { col: arr[0], row: arr[1], stat: "deny" } );
	});	
  });
</script>

<?= bottom() ?>