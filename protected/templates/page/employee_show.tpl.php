<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>

<h2><?= $params['personal']['full_name'] ?></h2><br/>

<?php if ( isset($params['successfully_added']) and $params['successfully_added'] ): ?>
	<div class="success">Сотрудник успешно добавлен</div>
<?php endif; ?>

<div class="human_container" style="overflow: hidden;">
	<div style="width: 160px; height: 100%; float: left;">
		here photo
	</div>
	<div style="float:left">
		<ul style="list-style: none; list-style-position: inside;">
			<li><?= $params['employee']['post'] ?></li>
			<li><?= $params['department']['name'] ?></li>
			<li>Дата начала работы: <?= $params['employee']['adoption_date'] ?></li>
			<li>Руководитель подразделения: <?= $params['employee']['chief'] === 'yes' ? 'да' : 'нет' ?></li>
			<?php if ( isset($params['employee']['phone']) ): ?>
				<li>Телефон: <?= $params['employee']['phone'] ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>