<?php
/*
	Модуль редактирования учебных групп
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');
$i = 0;

$groups = $db->query("
	SELECT 
		edu_groups.*,
		edu_curriculums.name as cname,
		org_departments.name as dname
	FROM 
		edu_groups
		LEFT JOIN edu_group_terms ON
			edu_groups.id = edu_group_terms.group_id
		LEFT JOIN edu_curriculums ON
			edu_group_terms.curriculum_id = edu_curriculums.id
		LEFT JOIN org_departments ON
			org_departments.id = edu_groups.department_id
	WHERE edu_groups.state = 'on'
");


//
// ВЫВОД
//
Template::top();
?>
<h2>Группы <a href="<?= WEBURL . 'edu/groups' ?>">(перейти к дереву)</a></h2>


<!-- ВЫВОД ГРУПП СПИСКОМ -->	
<table class="groups">
	<tr>
		<th>Название</th>
		<th>Учебный план</th>
		<th>Подразделение</th>
		<th>Редактировать</th>
	</tr>
<?php foreach ( $groups as $g ): ?>
	<tr <?= $i % 2 === 0 ? 'class="odd"' : '' ?>>
		<td><?= $g['name'] ?></td>
		<td><?= $g['cname'] ?></td>
		<td><?= $g['dname'] ?></td>
		<td>
			<a href="<?= WEBURL . 'edu/group/'. $g['id'] ?>">редактировать</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>


<?= Template::bottom() ?>