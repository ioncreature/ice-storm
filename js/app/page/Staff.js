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

dojo.provide( 'app.store.StaffDepartments' );
dojo.safeMixin( app.store.StaffDepartments, app.store.Departments, {} );

//
//
//app.store.Staff.add({ id: 1, first_name: 'Alex', last_name: 'Reese' });
//app.store.Staff.add({ id: 2, first_name: 'Ion', last_name: 'Creature' });
//




dojo.ready( function(){
	dojo.provide( 'app.page.Staff.Grid' );
	dojo.provide( 'app.page.Staff.Tree' );


	// Departments tree
	var tree_store = new dojo.store.Observable( app.store.Departments );
	app.page.Staff.Tree = new dijit.Tree({

		model: tree_store,

		onClick: function( department, self, event ){
			console.log( this.model.root );
			console.log( arguments );
			this.model.add({ id: 100, name: 'Sex and Drugs department', parent: 6, children: false });
		}

	}, 'staff_departments_tree' );


	// Staff table
	app.page.Staff.Grid = new dojox.grid.DataGrid({
		store: dojo.data.ObjectStore({objectStore: app.store.Staff}),
		rowsPerPage: 50 ,
		keepRows: 1000,
		structure: [{
			defaultCell: { width: '30%' },
			cells: [
				{ name: 'Должность', field: 'post' },
				{ name: 'ФИО', field: 'name' },
				{ name: 'Подразделение', field: 'department' }
			]
		}]
	}, 'staff_grid' );
	app.page.Staff.Grid.startup();
});