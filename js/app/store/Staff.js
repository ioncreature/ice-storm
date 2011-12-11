/**
 * @author Marenin Alex
 * December 2011
 */

dojo.provide( 'app.store.Staff' );
dojo.require( 'dojo.store.Memory' );

app.store.Staff = new dojo.store.JsonRest({
	target: app.config.service.staff
});
