<?php
/*
	Модуль вывода данных об учебной группе
	Marenin Alex
	October
	2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');

$gid = $r->to_int(2);
if ( !$gid )
	redirect( WEBURL );

$group = $db->fetch_query("
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
	WHERE 
		edu_groups.id = '$gid' and
		edu_groups.state = 'on'
");
if ( !$group )
	redirect( WEBURL );

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
		<td>Название</td>
		<td><?= htmlspecialchars($group['name']) ?></td>
	</tr>
	<tr>
		<td>Подразделение</td>
		<td><?= htmlspecialchars($group['dname']) ?></td>
	</tr>
	<tr>
		<td>Курс</td>
		<td><?= htmlspecialchars($group['name']) ?></td>
	</tr>
	<tr>
		<td>Курс</td>
		<td><?= htmlspecialchars($group['name']) ?></td>
	</tr>
</table>


<!-- СПИСОК СТУДЕНТОВ -->
<h2>Студенты группы <?= htmlspecialchars($group['name']) ?></h2>
<table>
	<tr>
		<th>ФИО</th>
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