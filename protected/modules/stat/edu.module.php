<?php
/**
 * Marenin Alex
 * Date: 11.11.11 22:29
 */



//
// ВЫВОД
//
Template::add_js( '/js/dojo/dojo.js' );
Template::top();
?>

<script type="text/javascript">
	dojo.require( 'dijit.layout.BorderContainer' );
	dojo.require( 'dijit.layout.TabContainer' );
	dojo.require( 'dijit.layout.ContentPane' );
	dojo.require( 'dojo.parser' );
	
	dojo.ready( function(){
		dojo.parser.parse();
	});
</script>


<div dojoType="dijit.layout.BorderContainer" glutters="true" design="sidebar"
	 style="margin: 0px; height: 100%; width: 100%">

	<section dojoType="dijit.layout.ContentPane" region="left">
		menu
	</section>

	<section dojoType="dijit.layout.TabContainer" region="center">
		<div dojoType="dijit.layout.ContentPane" id="edu_stat_general" title="Главная" selected="true">
			hello!
		</div>
		
		<div dojoType="dijit.layout.ContentPane" title="По студенту">
			student stats!
		</div>

		<div dojoType="dijit.layout.ContentPane" title="По группе">
			group stats!
		</div>
	</section>
	
</div>

