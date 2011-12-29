<?php
/**
 * @author Marenin Alex
 *         December 2011
 */


$r = \Request\Parser::get_instance();
$employee_id = $r->to_int(2);
$employee = new \Model\Employee( $employee_id ? $employee_id : false );
$human = $employee->Human;
$department = $employee->Department;

//
// ВЫВОД
//

Template::top();
?>


<!-- FORM -->
<form id="add_employee_form" action="<?= WEBURL .'org/employee/add' ?>" method="POST">
	Должность<input type="text" name="post" value="" />
	Подразделение<select name="department_id">
		<?php foreach( $department->get_all(1) as $d ): ?>
			<option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
		<?php endforeach; ?>
	</select>

	<br />Человечишко<br />
	<input type="radio" name="human_source" value="existing" checked />Выбрать существующего<br />
	<input type="radio" name="human_source" value="new" />Добавить нового<br />

	<select name="human_id">
		<?php foreach( $human->get_all() as $h ): ?>
			<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
		<?php endforeach; ?>
	</select>

	<div class="human_subform" style="display: none">
		Новый человечишко<br/>
		<input type="text" name="last_name" value="" />Фамилия<br/>
		<input type="text" name="first_name" value="" />Имя<br/>
		<input type="text" name="middle_name" value="" />Отчество<br/>
	</div>

	<br/><input type="text" name="work_phone" value="" />Рабочий телефон

	<br /><input type="submit" value="Добавить" />
</form>

<script type="text/javascript">
	$( '#add_employee_form input[name=human_source]' ).change( function( event ){
		if ( $(this).val() === 'new' ){
			$( 'select[name=human_id]' ).hide();
			$( 'div.human_subform' ).show();
		}
		else {
			$( 'select[name=human_id]' ).show();
			$( 'div.human_subform' ).hide();
		}
	});
</script>


<br /><br />
<table>
	<tbody>
		<tr>
			<td colspan="2">
				<i><?= htmlspecialchars($human->last_name .' '. $human->first_name .' '. $human->middle_name) ?></i>
			</td>
		</tr>
		<tr>
			<td>Должность</td>
			<td><?= htmlspecialchars($employee->post) ?></td>
		</tr>
		<tr>
			<td>Подразделение</td>
			<td><?= htmlspecialchars($department->name) ?></td>
		</tr>
	</tbody>
</table>


<?= Template::bottom(); ?>