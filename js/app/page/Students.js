/**
 * @author Marenin Alex
 * February 2012
 */

require([
	'app/store/Departments',
	'app/Service',
	'app/Page',
	'app/util',
	'dojo/store/Observable',
	'app/init'
], function( Departments, Service, Page, util ){

var page = dojo.declare( 'app.page.Students', Page, {

	/**
	 * Page entry point
	 */
	enterPage: function(){
		var self = this;

		// обновляем список студентов
		this.updateStudentsList();

		// форма поиска
		dojo.query( '#students_search' ).on( 'submit', function( e ){
			dojo.stopEvent( e );
			dojo.xhrGet({
				url: app.config.page.students +'search',
				content: dojo.formToObject( this )
			}).then( self.parseTable );
		});

		// Departments tree
//		new dijit.Tree({
//			model: new dojo.store.Observable( Departments ),
//
//			onClick: function( department, self, event ){
//				dojo.byId( 'depatrment_name' ).innerHTML = department.name;
//				self.updateStudentsList( department.id );
//			}
//		}, dojo.byId('staff_departments_tree') );

		// обработка табов
		var tabs = dijit.byId( 'center_tabs' );
		tabs.watch( 'selectedChildWidget', function( property, old_val, new_val ){
			if ( new_val !== tab_new )
				return;

			dojo.xhrGet({ url: app.config.page.students + 'new' }).then( function( resp ){
				tab_new.containerNode.innerHTML = resp;
				parser.parse( tab_new.containerNode );
			});
		});
	},


	/**
	 * Loads students of group
	 * @param {number?} group_id
	 */
	updateStudentsList: function( group_id ){
		var url = app.config.page.students + "group/" + (group_id ? group_id : '');
		dojo
			.xhrGet({ url: url })
			.then( this.parseTable );
	},


	/**
	 * Renders staff table
	 * @param data
	 */
	parseTable: function( data ){
		var students = { students: dojo.fromJson( data ) };
		$( '#student_grid' ).html( ich.t_students_table(students) );
	}

});

return new page();
});
