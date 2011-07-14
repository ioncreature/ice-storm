// Menu tree store
Ext.define( 'IceStorm.store.Tree', {
	extend: 'Ext.data.TreeStore',
	root: {
		text: 'Ext JS',
		children: [
			{
				text:'������������',
				expanded: true,
				children:[
					{
						text:'�������',
						id:'absolute',
						leaf:true
					},
					{
						text:'������',
						id:'accordion',
						leaf:true
					}
				]
			}
		]
	}
});