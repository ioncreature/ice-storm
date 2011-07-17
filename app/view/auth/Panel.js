Ext.define( 'IceStorm.view.auth.Panel', {
	extend: 'Ext.Panel',
	alias: 'widget.loginpanel',
	
	region: 'east',
	width: 200,
	margin: '4 0 4 4',
	items: [
		{
			xtype: 'button',
			text: 'Logout'
		}
	],
	
	init: function(){
		console.log( 'IceStorm.view.auth.Panel.init()' );
		this.control({
			render: this.onPanelRendered
		});
	}
});