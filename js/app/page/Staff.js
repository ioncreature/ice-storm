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

dojo.provide( 'app.store.StaffDepartments' );
dojo.safeMixin( app.store.StaffDepartments, app.store.Departments, {} );

//
//
//app.store.Staff.add({ id: 1, first_name: 'Alex', last_name: 'Reese' });
//app.store.Staff.add({ id: 2, first_name: 'Ion', last_name: 'Creature' });
//




dojo.ready( function(){

	var tree = dijit.byId( 'staff_departments_tree' );

	dojo.provide( 'app.StaffGrid' );
	app.StaffGrid = (function(){
		return new dojox.grid.DataGrid({
			store: dojo.data.ObjectStore({objectStore: app.store.Staff}),
			structure: [{
				defaultCell: { width: '30%' },
				cells: [
					{ name: 'ФИО', field: 'name' },
					{ name: 'Должность', field: 'post' },
					{ name: 'Подразделение', field: 'department' }
				]
			}]
		}, 'staff_grid' );
	})();
	app.StaffGrid.startup();
});