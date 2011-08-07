(function( $, undefined ){

// global App object
var App = {};
window.App = App;

// menu module
App.menu = {
	Controller: null
};

// view for main menu
App.menu.View = Backbone.View.extend({
	tagName: 'ul',
	className: 'tree_menu',
	initialize: function( to ){
		console.log( 'App.menu.View init' );
		// _.bindAll(this, 'render', 'add_item');
		this.elem = to;
		this.render( to );
		this.counter = 0;
	},
	
	events: {
		'click button#add': 'add_item'
	},
	test: function(e){console.log(e);},
	
	render: function( to ){
		console.log( 'App.menu.View render' );
		$( to ).append( "<button id='add'>Add list item</button>" );
		$( to ).append( '<ul><li>Hello list!</li></ul>' );
		this.add_item();
		return true;
	},
	
	add_item: function(){
		console.log('App.menu.View add_item');
		this.counter++;
		$('ul', this.elem ).append("<li>line "+ this.counter +"</li>");
    }
});

// collection for menu items
App.menu.Collection = Backbone.Collection.extend({
	model: App.menu.Model
});

// model for main menu
App.menu.Model = Backbone.Model.extend({});

$( document ).ready(function(){
	var data = new App.menu.Model({ title: 'main page', leaf: true });
	var menu = new App.menu.View( $( '#leftpanel' ) );
});

})( jQuery );
