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
	 * @param department_id
	 */
	function update_staff_list( department_id ){
		var url = app.config.service.staff + (department_id ? "department/" + department_id : '');
//		Service.get( url ).then( parse_table );
		dojo
			.xhrGet({ url: url })
			.then( parse_table );
	}


	/**
	 * Renders staff table
	 * @param data
	 */
	function parse_table( data ){
		var staff = { staff: dojo.fromJson( data ) };
		$( '#student_grid' ).html( ich.t_staff_table(staff) );
	}
	// get staff from root node
	update_staff_list();


	$( '#student_search' ).submit( function( event ){
		event.stopPropagation();
		event.preventDefault();

		dojo.xhrGet({ url: app.config.service.staff +'search/'+ dojo.formToObject(event.target).name })
			.then( app.page.Staff.parse_table );

		return false;
	});

	return {
		tree: tree,
		update_staff_list: update_staff_list,
		parse_table: parse_table
	};

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
