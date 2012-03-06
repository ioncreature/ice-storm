/**
 * @author Marenin Alex
 * March 2012
 */

define( 'app/Service', [
], function(){

	return {
		'get': function( service, params ){
			return dojo.xhrGet( service, params );
		},
		'post': function( service, params ){
			return dojo.xhrPost( service, params );
		},
		'delete': function( service, params ){
			return dojo.xhrDelete( service, params );
		}
	}
});