<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
$i = 0;
?>

<h2>Права доступа <?= $params['type'] === 'users' ? 'пользователей' : 'групп' ?></h2><br/>

<table class="common">
	<tr>
		<th>Право доступа\<?= $params['type'] === 'users' ? 'Пользователь' : 'Группа' ?></th>
		<?php foreach ( $params['subjects'] as $s ): ?>
			<th title="<?= $s['description'] ?>"><?= $s['name'] ?></th>
		<?php endforeach; ?>
	</tr>

	<?php foreach ( $params['permissions'] as $p ): ?>
		<tr <?= $i++ % 2 === 1 ? 'class="odd"' : ''?>>
			<td class="left"><?= $p['description'] ?></td>
			<?php foreach ( $params['subjects'] as $s ): ?>
				<td>
					<input
						type="checkbox"
						<?= isset( $p['subjects'][$s['id']] ) ? 'checked="checked"' : '' ?>
						value="<?= $p['id'] .','. $s['id'] ?>"
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
			$.post( '<?= WEBURL .$params['path'] ?>', {
				permission_id: arr[0],
				subject_id: arr[1],
				type: $( this ).is(':checked') ? "allow" : "deny"
			});
		});
	});
</script>
