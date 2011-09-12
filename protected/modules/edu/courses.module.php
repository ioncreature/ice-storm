<?php
/*
	Модуль создания учебных курсов
	Marenin Alex
	August 2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');
$i = 0;
$error = '';

$name = '';
$shortname = '';
$terms = '0';
$hours = '0';

if ( $r->equal('edu/courses/add') and isset($r->name, $r->shortname, $r->terms, $r->hours) ){
	$name = $db->safe( $r->name );
	$shortname = $db->safe( $r->shortname );
	$terms = (int) $r->terms;
	$hours = (int) $r->hours;
	try{
		if ( !($name and $shortname and $terms and $hours) )
			throw new Exception('Указаны не все данные');
		
		if ( !($terms and $hours) )
			throw new Exception('Некорректное количество часов или семестров');
		
		if ( $terms > 40 )
			throw new Exception('Количество семестров должно быть не больше 40-а');
		
		if ( $hours > 10000 )
			throw new Exception('Количество учебных часов в курсе должно быть не больше 10.000');
			
		$db->start();
		$c = $db->query("SELECT * FROM edu_courses WHERE name = '$name'");
		if ( $c )
			throw new Exception('Курс с таким именем уже существует');
		
		$cid = $db->insert( 'edu_courses', array(
			'name' => $r->name,
			'shortname' => $r->shortname,
			'hours' => $hours
		));
		
		for ( $i = 1; $i <= $terms; $i++ )
			$db->insert( 'edu_course_stages', array(
				'course_id' => $cid,
				'order' => $i,
				'hours' => 0
			));
		
		$db->commit();
		redirect( 'edu/courses' );
	}
	catch( Exception $e ){
		$db->rollback();
		$error .= $e->getMessage();
	}
}

// Список учебных курсов
$courses = $db->query("
	SELECT 
		edu_courses.*,
		COUNT(edu_course_stages.id) as stages
	FROM 
		edu_courses
		LEFT JOIN edu_course_stages ON edu_courses.id = edu_course_stages.course_id
	GROUP BY edu_courses.id
");

//
// ВЫВОД
//
Template::top();
?>

<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>


<!-- ДОБАВИТЬ УЧЕБНЫЙ КУРС -->
<h2>Добавить учебный курс</h2>
<style type="text/css">#c_container input[type=text]{ width: 300px; }</style>
<div id="c_container">
	<form method="POST" action="<?= WEBURL .'edu/courses/add' ?>">
		<label>Полное название курса<br />
			<input type="text" name="name" placeholder="полное название" <?= $name ? 'value="'.strip_tags($name).'"' : ''?> />
		</label><br />
		<label>Короткое название курса (не более 20-и символов)<br />
			<input type="text" name="shortname" placeholder="короткое название" <?= $shortname ? 'value="'.strip_tags($shortname).'"' : ''?> />
		</label><br />
		<label>Количество семестров<br />
			<input type="text" name="terms" placeholder="количество семестров" <?= $terms ? 'value="'.intval($terms).'"' : ''?> />
		</label><br />
		<label>Количество часов<br />
			<input type="text" name="hours" placeholder="количество часов" <?= $terms ? 'value="'.intval($terms).'"' : ''?> />
		</label>
		<input type="submit" value="Добавить" />
	</form>
	<div class="error" style="display:none;"></div>
</div>
<script type="text/javascript">
	$( '#c_container > form' ).submit( function(){
		var name = $( 'input[name=name]', this ).val();
		var shortname = $( 'input[name=shortname]', this ).val();
		var terms = $( 'input[name=terms]', this ).val();
		var hours = $( 'input[name=hours]', this ).val();
		var error = '';
		if ( name.length < 8 )
			error += 'Слишком короткое название учебного курса<br />';
		if ( shortname.length < 2 )
			error += 'Введите корректное краткое название курса<br />';
		if ( Number(terms) <= 0 || Number(terms) > 100 )
			error += 'Некорректное значение в поле "Количество семестров"<br />';
		if ( Number(hours) <= 0 || Number(terms) > 10000 )
			error += 'Некорректное значение в поле "Количество часов"<br />';
		if ( error.length > 0 ){
			$( 'div.error' ).html( error );
			$( 'div.error' ).show();
			return false;
		}
		return false;
	});
</script>
<br />



<!-- СПИСОК УЧЕБНЫХ КУРСОВ -->
<h2>Учебные курсы</h2>
<table class="common">
	<tr>
		<th>Название</th>
		<th width="120px">Количество<br />семестров</th>
		<th width="120px">Количество<br />часов</th>
	</tr>
	<?php foreach ( $courses as $c ): ?>
	<tr <?= $i++ % 2 === 1 ? 'class="odd"' : '' ?>>
		<td class="left"><a href="<?= WEBURL .'edu/course/'.$c['id'] ?>">
			<?= nl2br( htmlspecialchars($c['name']) ) ?>
			</a>
		</td>
		<td><?= $c['stages'] ?></td>
		<td><?= $c['hours'] ?></td>
	</tr>
	<?php endforeach; ?>
	<?php if ( ! $courses ): ?>
		<tr><td colspan="3"><b>Учебных курсов пока нет</b></td></tr>
	<?php endif; ?>
</table>


<?= Template::bottom() ?>