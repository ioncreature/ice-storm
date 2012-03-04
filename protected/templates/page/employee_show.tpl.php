<?php
/**
 * @author Marenin Alex
 *         January 2012
 */
?>

<?php if ( isset($data['edit']) ): ?>
	<a class="a-button" style="float: right;" href="<?= $data['edit'] ?>">редактировать</a>
<?php endif; ?>

<h2>Сотрудник <?= $data['personal']['full_name'] ?></h2>

<?php if ( isset($data['successfully_added']) and $data['successfully_added'] ): ?>
	<div class="success">Сотрудник успешно добавлен</div>
<?php endif; ?>

<div style="overflow: hidden;">
	<div style="float: left; width: 150px;">
		<div style="height: 200px; background-color: rgba(192, 192, 192, 0.5);"></div>
	</div>

	<style type="text/css">
		span.title {
			display: inline-block;
			width: 250px;
		}
	</style>
	<div style="float: left; margin-left: 20px;">
		<span class="title">Подразделение</span>
		<?= $data['department']['name'] ?><br/>
		<span class="title">Должность</span>
		<?= $data['employee']['post'] ?><br/>
		<span class="title">Дата начала работы</span>
		<?= $data['employee']['adoption_date'] ?><br/>
		<span class="title">Руководитель подразделения</span>
		<?= $data['employee']['chief'] === 'yes' ? 'да' : 'нет' ?><br/>
		<br/>

		<span class="title">Дата рождения</span>
		<?= $data['personal']['birth_date'] ?><br/>
		<span class="title">Рабочий телефон</span>
		<?= $data['employee']['phone'] ?><br/>
		<span class="title">Телефон</span>
		<?= $data['personal']['phone'] ?><br/>
		<span class="title">Email</span>
		<?= $data['personal']['email'] ?><br/>
	</div>
</div>

