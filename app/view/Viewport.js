/**
 * The main viewport, split in to a west and center region.
 * The North region should also be shown by default in the packaged
 * (non-live) version of the docs. TODO: close button on north region.
 */
Ext.define('IceStorm.view.Viewport', {
	extend: 'Ext.container.Viewport',
	requires: [
	],

	id: 'viewport',
	layout: 'border',
	defaults: { xtype: 'container' },

	initComponent: function() {
		this.items = [
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
					menuTree
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
		];

		this.callParent(arguments);
	}
});
