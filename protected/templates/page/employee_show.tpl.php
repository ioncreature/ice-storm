<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>

<?php if ( isset($data['edit']) ): ?>
	<a class="a-button" style="float: right;" href="<?= $data['edit'] ?>">редактировать</a>
<?php endif; ?>

<h2><?= $data['personal']['full_name'] ?></h2><br/>

<?php if ( isset($data['successfully_added']) and $data['successfully_added'] ): ?>
	<div class="success">Сотрудник успешно добавлен</div>
<?php endif; ?>

<div class="human_container" style="overflow: hidden;">
	<div style="width: 160px; height: 100%; float: left;">
		here photo
	</div>
	<div style="float:left">
		<ul style="list-style: none; list-style-position: inside;">
			<li><?= $data['employee']['post'] ?></li>
			<li><?= $data['department']['name'] ?></li>
			<li>Дата начала работы: <?= $data['employee']['adoption_date'] ?></li>
			<li>Руководитель подразделения: <?= $data['employee']['chief'] === 'yes' ? 'да' : 'нет' ?></li>
			<?php if ( isset($data['employee']['phone']) ): ?>
				<li>Телефон: <?= $data['employee']['phone'] ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>