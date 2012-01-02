<?php
/*
	Модуль вывода данных об учебной группе
	Marenin Alex
	October
	2011
*/

$r = \Request\Parser::get_instance();
$db = Fabric::get('db');

$gid = $r->to_int(2);
if ( !$gid )
	redirect( WEBURL );

$group = $db->fetch_query("
	SELECT 
		edu_groups.*,
		edu_curriculums.name as cname,
		org_departments.name as dname,
		edu_group_terms.date_end,
		edu_curriculum_terms.`order`
	FROM 
		edu_groups
		LEFT JOIN org_departments ON
			org_departments.id = edu_groups.department_id
		LEFT JOIN edu_group_terms ON
			edu_group_terms.group_id = edu_groups.id AND
			edu_group_terms.closed = 'no'
		LEFT JOIN edu_curriculums ON
			edu_curriculums.id = edu_group_terms.curriculum_id
		LEFT JOIN edu_curriculum_terms ON
			edu_curriculum_terms.id = edu_group_terms.curriculum_term_id
	WHERE 
		edu_groups.id = '$gid'
");
if ( !$group )
	redirect( WEBURL );

// var_dump( $group ); die;
$students = $db->query("
	SELECT 
		edu_students.*,
		org_humans.* 
	FROM
		edu_students
		LEFT JOIN org_humans ON org_humans.id = edu_students.human_id
	WHERE
		edu_students.group_id = '$gid'  
");


//
// ВЫВОД
//
Template::top();
?>
<h2>Группa <?= htmlspecialchars($group['name']) ?></h2>

<!-- ИНФОРМАЦИЯ О ГРУППЕ -->
<table>
	<tr>
		<td width="275px">Название</td>
		<td><?= htmlspecialchars($group['name']) ?></td>
	</tr>
	<tr>
		<td>Подразделение</td>
		<td><?= htmlspecialchars($group['dname']) ?></td>
	</tr>
	<tr>
		<td>Текущий учебный план</td>
		<td>
			<?= $group['cname'] ? htmlspecialchars($group['cname']) : '---' ?>
		</td>
	</tr>
	<tr>
		<td>Текущий семестр</td>
		<td>
			<?php if ( $group['order'] ):  ?>
				<?= $group['order'] ?> семестр
				<?= $group['date_end'] ? '(окончание - '. date('Y-m-d', strtotime($group['date_end'])) .')' : '' ?>
			<?php else: ?>
				---
			<?php endif; ?>
		</td>
	</tr>
</table><br />


<!-- СПИСОК СТУДЕНТОВ -->
<h2>Студенты</h2>
<table>
	<tr>
		<th width="275px">ФИО</th>
		<th>Дата рождения</th>
	</tr>
<?php foreach ( $students as $s ): ?>
	<tr>
		<td>
			<a href="<?= WEBURL .'edu/student/'. $s['id'] ?>">
				<?= htmlspecialchars($s['last_name'] .' '. $s['first_name'] .' '. $s['middle_name']) ?>
			</a>
		</td>
		<td><?= htmlspecialchars($s['birth_date']) ?></td>
	</tr>
<?php endforeach; ?>
</table>


<?= Template::bottom() ?>