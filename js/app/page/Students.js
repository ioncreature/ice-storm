/**
 * @author Marenin Alex
 * February 2012
 */

require([
	'app/store/Departments',
	'app/Service',
	'dojo/store/Observable',
	'app/init'
], function( Departments, Service ){

/**
	 * Departments tree
	 */
	var tree = new dijit.Tree({
		model: new dojo.store.Observable( Departments ),

		onClick: function( department, self, event ){
			dojo.byId( 'depatrment_name' ).innerHTML = department.name;
			update_staff_list( department.id );
		}
	}, dojo.byId('staff_departments_tree') );


	/**
	 * Loads staff of department
	 * @param {number?} group_id
	 */
	function update_students_list( group_id ){
		var url = app.config.page.students + "group/" + (group_id ? group_id : '');
		dojo
			.xhrGet({ url: url })
			.then( parse_table );
	}


	/**
	 * Renders staff table
	 * @param data
	 */
	function parse_table( data ){
		var students = { students: dojo.fromJson( data ) };
		console.log( students, ich.t_students_table(students) );
		$( '#student_grid' ).html( ich.t_students_table(students) );
	}
	update_students_list();


	$( '#student_search' ).submit( function( e ){
		dojo.stopEvent( e );

		dojo.xhrGet({ url: app.config.page.students +'search/'+ dojo.formToObject(event.target).name })
			.then( parse_table );

		return false;
	});

	/*
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
	*/
});
