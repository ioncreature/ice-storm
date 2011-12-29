<?php
/**
 * Staff module
 * Marenin Alex
 * November 2011
 */


// PREPARE
Template::add_css( '/js/dijit/themes/claro/claro.css' );
Template::add_js( '/js/dojo/dojo.js' );
Template::add_js( '/js/app/init.js' );
Template::add_js( '/js/app/page/Staff.js' );
Template::add_js( '/js/ICanHaz.js' );

// OUTPUT
Template::ob_to_block( 'body' );
?>
<div
	dojoType="dijit.layout.BorderContainer"
	gutters="true"
	design="sidebar"
	style="min-height: 760px;">

	<!-- LEFT PANE -->
	<section id="left_pane" dojoType="dijit.layout.ContentPane" region="left" style="width: 35%; min-width: 350px; padding: 0px;">
		<div dojoType="dijit.layout.BorderContainer" gutters="false">
			<div dojoType="dijit.layout.ContentPane" region="top">
				<h1>Подразделения</h1>
			</div>

			<!-- DEPARTMENTS TREE -->
			<div dojoType="dijit.layout.ContentPane" region="center">
				<div id="staff_departments_tree" style="height:100%"></div>
			</div>
		</div>
	</section>

	<!-- CENTER PANE -->
	<section id="center_pane" dojoType="dijit.layout.ContentPane" region="center">
		<div style="overflow: hidden">
			<h2 id="depatrment_name" style="height: 30px; width: 70%; float: left;">Все сотрудики</h2>
			<div style="float: right; width: 29%; text-align: right;">
				<form id="staff_search" action="<?= WEBURL .'service/staff/search/' ?>" method="POST">
					<input name="name" type="text" />
				</form>
			</div>
		</div>
		<div style="text-align: right;">
			<a href="<?= WEBURL .'org/employee/new' ?>"><button>Новый сотрудник</button></a>
		</div>
		<div id="staff_grid"></div>
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

<?php Template::ob_end(); ?>
