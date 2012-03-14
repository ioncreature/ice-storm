/**
 * @author Marenin Alex
 * March 2012
 */

define( 'app/Page', [
	'dojo/_base/declare'
], function( declare ){
return declare( 'app.Page', null, {

	constructor: function( params ){
		dojo.safeMixin( this, params );

		this.enterPage();
	},

	enterPage: function(){}

});

});
