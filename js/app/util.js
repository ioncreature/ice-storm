/**
 * @author Marenin Alex
 * March 2012
 */

define( 'app/util', [], function(){

return {

	hitch: function( context, fn, args ){
		if ( typeof fn !== 'function' )
			throw new Error( 'util.hitch:: second argument must be a function' );

		return function(){
			return fn.apply( context, Array.prototype.slice.call( arguments, 0 ).concat(args || [], this) );
		}
	}

};

});