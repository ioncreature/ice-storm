<?php
/*
	Модуль редактирования учебных групп
	Marenin Alex
	October
	2011
*/

$r = \Request\Parser::get_instance();
$db = Fabric::get('db');
$i = 0;


// поля формы по умолчанию
$f_name = "";
$f_department_id = 0;


// добавление группы
if ( $r->equal('edu/groups_list/add') and isset($r->name, $r->department) ){
	$name = $db->safe( $r->name );
	$did = (int) $r->department;
	$db->start();
	$g = $db->query( "SELECT * FROM edu_groups WHERE name = '$name' LIMIT 1" );
	if ( $g ){
		$f_name = $name;
		$f_department_id = $did;
		$error = 'Группа с таким именем уже существует';
		$db->rollback();
	}
	// сохранение
	else {
		$db->insert( 'edu_groups', array(
			'name' => $r->name,
			'department_id' => $did
		));
		$db->commit();
		redirect( WEBURL .'edu/groups_list' );
	}
}


// редактирование группы
if ( $r->equal('edu/groups_list/edit/::int') ){
	$gid = $r->to_int(3);
	$g = $db->query("
		SELECT 
			name, department_id 
		FROM 
			edu_groups 
		WHERE
			id = '$gid'
	");
	$f_name = $g['name'];
	$f_department_id = (int) $g['department_id'];
}


// список групп
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


$departments = $db->cached_query("
	SELECT *
	FROM org_departments
	WHERE
		org_departments.state = 'active'
	ORDER BY org_departments.name
", 60 );


//
// ВЫВОД
//
Template::top();
?>
<h2>Группы <a href="<?= WEBURL . 'edu/groups' ?>">(перейти к дереву)</a></h2>



<!-- Добавление/редактирование групп -->
<br /><h3>Добавить группу</h3>
<form action="<?= WEBURL ."edu/groups_list/add" ?>" method="POST">
	<label>
		<span style="width: 150px; display: inline-block;">Название группы</span>
		<input type="text" name="name" value="<?= $f_name ?>" />
	</label>
	<label>
		<span style="width: 150px; display: inline-block;">Подразделение</span>
		<select name="department">
		<?php foreach ( $departments as $d ): ?>
			<option value="<?= $d['id'] ?>" <?= $f_department_id == $d['id'] ? 'SELECTED' : '' ?>>
				<?= $d['name'] ?>
			</option>
		<?php endforeach; ?>
		</select>
	</label>
	<?php if ( isset($error) ): ?>
		<div class="error"><?= htmlspecialchars($error) ?></div>
	<?php endif; ?>
	<input type="submit" value="Отправить" />
</form><br /><br />


<!-- ВЫВОД ГРУПП СПИСКОМ -->
<h3>Список групп</h3>	
<table class="common">
	<tr>
		<th>Название</th>
		<th>Учебный план</th>
		<th>Подразделение</th>
		<th>Редактировать</th>
	</tr>
<?php foreach ( $groups as $g ): ?>
	<tr <?= ++$i % 2 === 0 ? 'class="odd"' : '' ?>>
		<td>
			<a href="<?= WEBURL . 'edu/group/'. $g['id'] ?>"><?= $g['name'] ?></a>
		</td>
		<td><?= $g['cname'] ?></td>
		<td><?= $g['dname'] ?></td>
		<td>
			<a href="<?= WEBURL . 'edu/groups_list/edit/'. $g['id'] ?>">редактировать</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>


<?= Template::bottom() ?>