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
		'Window'
    ],

    // autoCreateViewport: true,

    launch: function() {
        IceStorm.App = this;
		Ext.tip.QuickTipManager.init();
	
		
		// Viewport
		Ext.create( 'Ext.Viewport', {
			layout: 'border',
			title: 'Ext Layout Browser',
			items: [
				{
					xtype: 'box',
					id: 'header',
					region: 'north',
					html: 'hello!',
					style: {
						margin: '6px 6px 4px 6px',
						backgroundColor: '#FFF'
					},
					height: 40
				},
				{
					region: 'west',
					id: 'layout-browser',
					border: false,
					split:true,
					margins: '2 0 6 6',
					width: 275,
					minSize: 100,
					maxSize: 500,
					items: [
						{
							store: 'IceStorm.store.Tree',
							id: 'tree-panel',
							title: 'Навигация',
							split: true,
							height: 360,
							minSize: 150,
							rootVisible: false,
							autoScroll: true,
							height: '100%'
						}
					]
				}, 
				{
					id: 'content-panel',
					region: 'center',
					layout: 'card',
					margins: '2 6 6 0',
					activeItem: 0,
					border: false
				}
			],
			renderTo: document.body
		});
    }
});