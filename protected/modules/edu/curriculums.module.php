<?php
/*
	Модуль работы с учебными планами
	Marenin Alex
	August 2011
*/

$r = RequestParser::get_instance();
$db = Fabric::get('db');
$i = 0;

// Добавление нового учебного плана
if ( $r->equal('edu/curriculums/add') and isset($r->name, $r->count) ){
	try {
		$name = $r->name;
		$count = (int) $r->count;
		
		if ( !$count and mb_strlen($name) <= 8 )
			throw new Exception( 'Вы ввели некорректные данные для создания нового учебного плана' );
		
		$curr = $db->fetch_query( "SELECT * FROM edu_curriculums WHERE name = '". $db->safe($name) ."'" );
		if ( $curr )
			throw new Exception( 'Вы ввели некорректные данные для создания нового учебного плана' );
	
		$db->insert( 'edu_curriculums', array(
			'state' => 'active',
			'name' => $name,
			'terms_count' => $count
		));
		redirect( WEBURL .'edu/curriculums' );
	}
	catch ( Exception $e ){
		$error = $e->getMessage();
	}
}

// Список учебных планов
$curriculums = $db->query("SELECT * FROM edu_curriculums");

//
// ВЫВОД
//
Template::top();
?>

<!-- СООБЩЕНИЕ ОБ ОШИБКЕ -->
<?php if ( !empty($error) ): ?>
	<div class="error"><?= $error ?></div>
<?php endif; ?>


<!-- НОВЫЙ УЧЕБНЫЙ ПЛАН -->
<h2>Добавить учебный план</h2>
<div id="c_container">
	<form method="POST" action="<?= WEBURL .'edu/curriculums/add' ?>">
		<label>Название плана<br />
			<input type="text" name="name" placeholder="название плана" style="width: 350px" />
		</label><br />
		<label>Количество семестров<br />
			<input type="text" name="count" placeholder="количество семестров" style="width: 350px" />
		</label><br />
		<input type="submit" value="Добавить" />
	</form>
	<div class="error" style="display:none;"></div>
</div>
<script type="text/javascript">
	$( '#c_container > form' ).submit( function(){
		var name = $( 'input[name=name]', this ).val();
		var count = $( 'input[name=count]', this ).val();
		var error = '';
		if ( name.length < 8 )
			error += 'Слишком короткое название учебного плана<br />';
		if ( Number(count) <= 0 || Number(count) > 100 )
			error += 'Некорректное значение в поле "Количество семестров"<br />';
		if ( error.length > 0 ){
			$( 'div.error' ).html( error );
			$( 'div.error' ).show();
			return false;
		}
	});
</script>
<br />


<!-- СПИСОК УЧЕБНЫХ ПЛАНОВ -->
<h2>Учебные планы</h2>
<table class="common">
	<tr>
		<th>Название</th>
		<th width="120px">Количество<br />семестров</th>
	</tr>
	<?php foreach ( $curriculums as $c ): ?>
	<tr <?= $i++ % 2 === 1 ? 'class="odd"' : '' ?>>
		<td class="left"><?= nl2br( htmlspecialchars($c['name']) ) ?></td>
		<td><?= $c['terms_count'] ?></td>
	</tr>
	<?php endforeach; ?>
	<?php if ( ! $curriculums ): ?>
		<tr><td colspan="2"><b>Учебных планов пока нет</b></td></tr>
	<?php endif; ?>
</table>

<?= Template::bottom() ?>