/**
 * @author Marenin Alex
 * December 2011
 */

dojo.provide( 'app.store.Departments' );
dojo.require( 'dojo.store.JsonRest' );

(function(){
    app.store.Departments = new dojo.store.JsonRest({
        target: app.config.service.department,

        mayHaveChildren: function(){
            return true;
        },

        getChildren: function( object, onComplete, onError ){
            this
                .get( object.id )
                .then( function( fullObject ){
                    object.children = fullObject.children;
                    onComplete( fullObject.children );
                }, onError );
        },

        getRoot: function( onItem, onError ){
			var self = this;
			if ( !this.rootElement )
				this.get( "" ).then( function( result ){
					onItem( result );
					self.rootElement = result;
				}, onError );
			else
				onItem( this.rootElement );
        },

		query: function(){
			return this.inherited( arguments );
		},

        getLabel: function( department ){
            return department.name;
        }
    });
})();