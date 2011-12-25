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
            this.get( "" ).then( onItem, onError );
        },

		query: function(){
			console.error(arguments);
			return this.inherited( arguments );
		},

        getLabel: function( dep ){
            return dep.name;
        }
    });
})();