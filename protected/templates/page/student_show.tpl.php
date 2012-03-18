<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

?>

<?php if ( $data['edit'] ): ?>
	<a class="a-button" style="float: right;" href="<?= WEBURL .'edu/students/new'. $data['student']->id ?>">редактировать</a>
<?php endif; ?>

<h2>Студент <?= $data['student']->Human->full_name ?></h2>

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
		<span class="title">Дата рождения</span>
		<?= $data['student']->Human->birth_date ?><br/>
		<span class="title">Телефон</span>
		<?= $data['student']->Human->phone ?><br/>
		<span class="title">Email</span>
		<?= $data['student']->Human->email ?><br/>
		<br/>


		<span class="title">Группа</span>
		<?= $data['student']->Group->name ?><br/>
		<span class="title">Дата зачисления</span>
		<?= $data['student']->enrollment_date ?><br/>
		<span class="title">Приказ о зачислении</span>
		<?= $data['student']->enrollment_order ?><br/>

		<br/>
		<span class="title">Дата отчисления</span>
		<?= $data['student']->dismissal_date ?><br/>
		<span class="title">Приказ об отчислении</span>
		<?= $data['student']->dismissal_order ?><br/>
		<span class="title">Причина отчисления</span>
		<?= $data['student']->dismissal_reason ?><br/>
	</div>
</div>
