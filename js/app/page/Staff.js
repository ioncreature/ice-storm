/**
 * Controller for staff module
 * Marenin Alex
 * November 2011
 */

dojo.provide( 'app.page.Staff' );

dojo.require( 'app.store.Departments' );
dojo.require( 'app.page.Staff' );
dojo.require( 'app.store.Staff' );
dojo.require( 'dojo.data.ItemFileReadStore' );
dojo.require( 'dojox.grid.DataGrid' );
dojo.require( 'dojo.data.ObjectStore' );
dojo.require( 'dijit.Tree' );
dojo.require( 'dijit.TitlePane' );
dojo.require( 'dijit.form.ValidationTextBox' );
dojo.require( 'dojo.store.Observable' );


// Applying to page content
dojo.ready( function(){

	/**
	 * Departments tree
	 */
	app.page.Staff.Tree = new dijit.Tree({
		model: new dojo.store.Observable( app.store.Departments ),

		onClick: function( department, self, event ){
			dojo.byId( 'depatrment_name' ).innerHTML = department.name;
			app.page.Staff.update_staff_list( department.id );
		}
	}, 'staff_departments_tree' );


	/**
	 * Loads staff of department
	 * @param department_id
	 */
	app.page.Staff.update_staff_list = function( department_id ){
		var url = app.config.service.staff + (department_id ? "department/" + department_id : '');
		dojo
			.xhrGet({ url: url })
			.then( app.page.Staff.parse_table );
	}

	/**
	 * Renders staff table
	 * @param data
	 */
	app.page.Staff.parse_table = function( data ){
		var staff = { staff: dojo.fromJson( data ) };
		$( '#staff_grid' ).html( ich.t_staff_table(staff) );
	}

	// get staff from root node
	app.page.Staff.update_staff_list();
});