<?php
/*
	Модуль редактирования учебных групп
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

$groups = $db->query("
	SELECT 
		edu_groups.*,
		edu_curriculums.name as cname
	FROM 
		edu_groups
		LEFT JOIN edu_group_terms ON
			edu_groups.id = edu_group_terms.group_id
		LEFT JOIN edu_curriculums ON
			edu_group_terms.curriculum_id = edu_curriculums.id
	WHERE edu_groups.state = 'on'
");


//
// ВЫВОД
//
Template::top();
?>
<h2>Группы</h2>


<?php if ( $r->equal('edu/groups/show/list') or $r->equal('edu/groups') ): ?>
<!-- ВЫВОД ГРУПП СПИСКОМ -->	
	<table class="groups">
		<tr>
			<th>Название</th>
			<th>Учебный план</th>
		</tr>
	<?php foreach ( $groups as $g ): ?>
		<tr>
			<td><?= $g['name'] ?></td>
			<td><?= $g['cname'] ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>


<?= Template::bottom() ?>