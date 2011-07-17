/**
 * The main viewport, split in to a west and center region.
 * The North region should also be shown by default in the packaged
 * (non-live) version of the docs. TODO: close button on north region.
 */
Ext.define('IceStorm.view.Viewport', {
	extend: 'Ext.container.Viewport',
	requires: [	
		'IceStorm.view.auth.Panel'
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
				layout: 'border',
				style: {
					margin: '6px 6px 4px 6px',
					backgroundColor: '#FFF'
				},
				height: 40,
				items: [
					{
						xtype: 'loginpanel',
						region: 'east'
					}
				]
			},
			{
				region: 'west',
				id: 'right-container',
				border: false,
				split:true,
				margins: '2 0 6 6',
				width: 275,
				minSize: 100,
				maxSize: 500,
				items: [
					{
						id: 'tree-panel',
						// store: 'IceStorm.store.Tree',
						store: 'menuTree',
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
				xtype: 'panel',
				region: 'center',
				layout: 'auto',
				margins: '2 6 6 0',
				activeItem: 0,
				html: 'hello',
				border: false
			}
		];
		this.callParent(arguments);
	},
	renderTo: document.body
});
