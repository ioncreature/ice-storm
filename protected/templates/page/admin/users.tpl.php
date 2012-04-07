<?php
/**4
 * @author Marenin Alex
 *         March 2012
 */
?>

<h2>Пользователи</h2>

<table class="common">
	<tr>
		<th>ID</th>
		<th>Логин</th>
		<th>ФИО</th>
	</tr>
<?php foreach ( $data['users'] as $u ): ?>
	<tr>
		<td><?= $u['id'] ?></td>
		<td>
			<a href="">
				<?= $u['login'] ?>
			</a>
		</td>
		<td><?= $u['full_name'] ?></td>
	</tr>
<?php endforeach; ?>
</table>