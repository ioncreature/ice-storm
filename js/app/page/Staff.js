/**
 * Controller for staff module
 * Marenin Alex
 * November 2011
 */

require([
	'app/store/Departments',
	'dojo/data/ItemFileReadStore',
	'dojo/data/ObjectStore',
	'dojo/on',
	'dijit/Tree',
	'dijit/TitlePane',
	'dijit/form/ValidationTextBox',
	'dojo/store/Observable',
	'app/init'
], function( Departments ){

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
		$( '#staff_grid' ).html( ich.t_staff_table(staff) );
	}
	// get staff from root node
	update_staff_list();


	$( '#staff_search' ).submit( function( event ){
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
});