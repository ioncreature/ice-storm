<?php
/**
 * Marenin Alex
 * Date: 11.11.11 22:29
 */



//
// ВЫВОД
//
//Template::add_js( '/js/dojo/dojo.js', array( 'djConfig' => 'parseOnLoad: true, isDebug: true' ));
//Template::add_js( 'https://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js',
//				  array( 'djConfig' => 'parseOnLoad: true, isDebug: true, gfxRenderer: "svg,silverlight,vml"' ));
Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_js ( WEBURL .'js/dojo/dojo.js');

Template::top();
?>


<script type="text/javascript">
	require(['app/init']);
	// x and y coordinates used for easy understanding of where they should display
	// Data represents website visits over a week period
	chartData = [
		{ x: "1", y: "19021" },
		{ x: "1", y: "24882" },
		{ x: "1", y: "11833" },
		{ x: "1", y: "16122" }
	];
	chart2Data = [
		{ x: "1", y: "33021" },
		{ x: "1", y: "2837" },
		{ x: "1", y: "24882" },
		{ x: "1", y: "17654" },
		{ x: "1", y: "9833" },
	];
	chart3Data = [
		{ x: "1", y: "9021" },
		{ x: "1", y: "22837" },
		{ x: "1", y: "29378" },
		{ x: "1", y: "24882" },
		{ x: "1", y: "17654" },
		{ x: "1", y: "12833" }
	];

</script>

<div class="claro" style="height: 500px;">

<div dojoType="dijit.layout.BorderContainer" gutters="false" design="sidebar"
	 style="margin: 0px; height: 100%; width: 100%">
	<!--
	<section dojoType="dijit.layout.ContentPane" region="left" style="width: 120px;">
		menu
	</section>
	-->

	<section dojoType="dijit.layout.TabContainer" region="center">
		<div dojoType="dijit.layout.ContentPane" id="edu_stat_general" title="Главная" selected="true">
			<!-- create the chart -->
			<div dojoType="dojox.charting.widget.Chart"
				 theme="dojox.charting.themes.Claro"
				 id="viewsChart"
				 style="width: 350px; height: 350px;">

				<!-- Pie Chart: add the plot -->
				<div class="plot"
					 name="default"
					 type="Pie"
					 radius="160"
					 fontColor="#000"
					 labelOffset="-20"></div>

				<!-- pieData is the data source -->
				<div class="series" name="Last Week's Visits" array="chartData"></div>

			</div>
		</div>
		
		<div dojoType="dijit.layout.ContentPane" title="По студенту">
			<!-- create the chart -->
			<div dojoType="dojox.charting.widget.Chart"
				 theme="dojox.charting.themes.Claro"
				 id="viewsChart2"
				 style="width: 350px; height: 350px;">

				<!-- Pie Chart: add the plot -->
				<div class="plot"
					 name="default"
					 type="Pie"
					 radius="160"
					 fontColor="#000"
					 labelOffset="-20"></div>

				<!-- pieData is the data source -->
				<div class="series" name="Last Week's Visits" array="chart2Data"></div>

			</div>
		</div>

		<div dojoType="dijit.layout.ContentPane" title="По группе">
			<!-- create the chart -->
			<div dojoType="dojox.charting.widget.Chart"
				 theme="dojox.charting.themes.Claro"
				 id="viewsChart3"
				 style="width: 350px; height: 350px;">

				<!-- Pie Chart: add the plot -->
				<div class="plot"
					 name="default"
					 type="Pie"
					 radius="160"
					 fontColor="#000"
					 labelOffset="-20"></div>

				<!-- pieData is the data source -->
				<div class="series" name="Last Week's Visits" array="chart3Data"></div>

			</div>
		</div>
	</section>
	
</div>

</div>

<?= Template::bottom() ?>