/**
 * @author Marenin Alex
 * February 2012
 */

require([
	'dojo/parser',
	'app/init',
	'dojo/domReady!',
], function( parser ){

	var tabs = dijit.byId( 'center_tabs' ),
		tab_new = dijit.byId( 'new_student' );

	tabs.watch( 'selectedChildWidget', function( property, old_val, new_val ){
		if ( new_val !== tab_new )
			return;

		dojo.xhrGet({ url: app.config.page.students + 'new' }).then( function( resp ){
			console.log( resp );
			tab_new.containerNode.innerHTML = resp;
			parser.parse( tab_new.containerNode );
		});
	});


	dojo.connect( dojo.byId('test'), 'onclick', function(){
		tabs.selectChild( tab_new );
	});

return {};
});
