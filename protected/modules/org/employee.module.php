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

<table>
	<tbody>
		<tr>
			<td colspan="2"><i><?= htmlspecialchars($human->last_name .' '. $human->first_name .' '. $human->middle_name) ?></i></td>
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