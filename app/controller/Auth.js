Ext.define('IceStorm.controller.Auth', {
    extend: 'Ext.app.Controller',
	
	views: [
        'IceStorm.view.auth.Panel'
    ],
	
	init: function(){
		console.log('Auth.init()');
	}
})