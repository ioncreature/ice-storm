<?php
$r = \Request\Parser::get_instance();
$db = Fabric::get('db');

if ( isset( $r->user_id, $r->group_id, $r->stat ) ){
	$user_id = (int) $r->user_id;
	$group_id = (int) $r->group_id;
	$type = mb_strtolower($r->stat) === 'yes' ? 'yes' : 'no';
	
	if ( mb_strtolower($r->stat) === 'yes' ){
		// проверка
		$pres = $db->fetch_query("
			SELECT * 
			FROM auth_users_groups
			WHERE
				group_id = '$group_id' and
				user_id = '$user_id'
			LIMIT 1
		");
		
		if ( !$pres )
			$db->insert( 'auth_users_groups', array(
				'group_id' => $group_id,
				'user_id' => $user_id,
			));
	}
	else {
		$db->query("
			DELETE 
			FROM auth_users_groups 
			WHERE
				group_id = '$group_id' and
				user_id = '$user_id'
			LIMIT 1
		");
	}
	die( json_encode( array( 'status' => true )));
}

// список групп
$groups = $db->query("SELECT * FROM auth_groups");

// список пользователей
$users = $db->query("SELECT * FROM auth_users");
$i = 0;

//
// ВЫВОД
//
Template::top();
?>

<h2>Пользователи и группы</h2><br/>

<table class="common">
	<tr>
		<th>Пользователь\группа</th>
		<?php foreach ( $groups as $g ): ?>
			<th><?= $g['name'] ?></th>
		<?php endforeach; ?>
	</tr>
	<?php foreach ( $users as $u ): 
			$gpus = $db->query("
				SELECT user_id, group_id
				FROM auth_users_groups
				WHERE user_id = '{$u['id']}'
			");
			$gp = array();
			foreach ( $gpus as $gp_us ){
				$gp[$gp_us['group_id']] = 1;
			}
	?>
	<tr <?= $i++ % 2 === 1 ? 'class="odd"' : '' ?>>
		<td><?= $u['login'] ?></td>
		<?php foreach ( $groups as $gr ): ?>
			<?php if ( isset($gp[$gr['id']]) ): ?>
				<td><input type="checkbox" name="gp_checkbox" checked="checked" value="<?= $u['id'] .','. $gr['id'] ?>"/><br></td>
			<?php else: ?>
				<td><input type="checkbox" name="gp_checkbox" value="<?= $u['id'] .','. $gr['id'] ?>"/><br></td>
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
			var url = '<?= WEBURL .'acl/usersingroups' ?>';
			if ( $(this).is(':checked') )
				$.post( url, { user_id: arr[0], group_id: arr[1], stat: "yes" } );
			else
				$.post( url, { user_id: arr[0], group_id: arr[1], stat: "no" } );
		});	
	});
</script>

<?= Template::bottom() ?>