/**
 * Main application definition for Docs app.
 *
 * We define our own Application class because this way we can also
 * easily define the dependencies.
 */
Ext.define('IceStorm.Application', {
    extend: 'Ext.app.Application',
    name: 'IceStorm',

    requires: [
    ],

    controllers: [
		'Window',
		'Auth'
    ],

    autoCreateViewport: true,

    launch: function() {
        IceStorm.App = this;
		
    }
});