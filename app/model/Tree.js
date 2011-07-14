// Menu tree store
Ext.define( 'IceStorm.store.Tree', {
	extend: 'Ext.data.TreeStore',
	root: {
		text: 'Ext JS',
		children: [
			{
				text:'Успеваемость',
				expanded: true,
				children:[
					{
						text:'Тратата',
						id:'absolute',
						leaf:true
					},
					{
						text:'Тутуту',
						id:'accordion',
						leaf:true
					}
				]
			}
		]
	}
});