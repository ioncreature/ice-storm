<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

// PREPARE
Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_js ( WEBURL .'js/dojo/dojo.js' );
Template::add_js ( WEBURL .'js/app/page/Students.js' );
?>

<div
	data-dojo-type="dijit.layout.BorderContainer"
	gutters="true"
	design="sidebar"
	style="min-height: 760px;">

	<!-- LEFT PANE -->
	<section id="left_pane" data-dojo-type="dijit.layout.ContentPane" region="left" style="width: 35%; min-width: 350px; padding: 0px;">
		<div data-dojo-type="dijit.layout.BorderContainer" gutters="false">
			<div data-dojo-type="dijit.layout.ContentPane" region="top">
				<h1>Подразделения</h1>
			</div>
			<!-- DEPARTMENTS TREE -->
			<div data-dojo-type="dijit.layout.ContentPane" region="center">
				<div id="staff_departments_tree" style="height:100%"></div>
			</div>
		</div>
	</section>

	<!-- CENTER PANE -->
	<section id="center_tabs" data-dojo-type="dijit.layout.TabContainer" region="center">

		<section id="students_list" data-dojo-type="dijit.layout.ContentPane" title="Все">
			<div style="overflow: hidden; padding: 4px 0px 12px 0px;">
				<?php if ( $data['can_add'] ): ?>
					<div data-dojo-type="dijit.form.Button" onclick="window.location.href='<?= WEBURL .'edu/students/new' ?>'">Новый студент</div>
				<?php endif; ?>
				<div style="float: right; width: 50%; text-align: right; padding: 2px;">
					<form id="staff_search" action="<?= WEBURL .'service/staff/search/' ?>" method="POST">
						Поиск&nbsp;<input name="name" type="text" class="common" style="display: inline-block; width:200px; margin:0px; padding:0px;" />
					</form>
				</div>
			</div>
			<div id="student_grid"></div>
		</section>

	</section>
</div>


<!-- JS TEMPLATES -->
<script type="text/html" id="t_staff_table">
	<table class="common staff">
		<thead>
			<tr>
				<th width="30%">ФИО</th>
				<th width="40%">Должность</th>
				<th width="30%">Подразделение</th>
			</tr>
		</thead>
		<tbody>
		{{#staff}}{{>t_staff_rows}}{{/staff}}
		{{^staff}}<tr><td colspan="3">Сотрудников нет</td></tr>{{/staff}}
		</tbody>
	</table>
</script>


<script type="text/html" id="t_staff_rows" class="partial">
	<tr>
		<td><a href="<?= WEBURL . 'org/employee/' ?>{{id}}">{{name}}</a></td>
		<td>{{post}}</td>
		<td>{{department}}</td>
	</tr>
</script>