var widgets = {};
var stores = {};


// Run application when onReady fired
Ext.onReady( function(){
	
	Ext.tip.QuickTipManager.init();
	
	
	// Menu tree store
	stores.menuTreeStore = Ext.create( 'Ext.data.TreeStore', {
        root: {
            expanded: true,
			text: 'Root',
			children: [
				{
					text:'Absolute',
					id:'absolute',
					leaf: true
				},
				{
					text:'Accordion',
					id:'accordion',
					leaf: true
				}
			]
        }
        // proxy: {
            // type: 'ajax',
            // url: 'tree.json'
        // }
    });
	
	// Menu tree panel
	widgets.menuTree = Ext.create( 'Ext.tree.Panel', {
        id: 'tree-panel',
        title: 'Навигация',
        region: 'north',
        split: true,
        height: 360,
        minSize: 150,
        rootVisible: false,
        autoScroll: true,
        store: stores.menuTreeStore
    });
	
	
	 // Assign the changeLayout function to be called on tree node click.
    widgets.menuTree.getSelectionModel().on('select', function(selModel, record) {
        if (record.get('leaf')) {
            Ext.getCmp('content-panel').layout.setActiveItem(record.getId() + '-panel');
             if (!detailEl) {
                var bd = Ext.getCmp('details-panel').body;
                bd.update('').setStyle('background','#fff');
                detailEl = bd.createChild(); //create default empty div
            }
            detailEl
				.hide()
				.update(Ext.getDom(record.getId() + '-details').innerHTML)
				.slideIn('l', {stopAnimation:true,duration: 200});
        }
    })

	
	// Viewport
	Ext.create( 'Ext.Viewport', {
        layout: 'border',
        title: 'Ext Layout Browser',
        items: [
			{
				xtype: 'box',
				id: 'header',
				region: 'north',
				html: '<h1>Ext.Layout.Browser</h1>',
				height: 30
			},
			{
				layout: 'border',
				region: 'west',
				id: 'layout-browser',
				border: false,
				split:true,
				margins: '2 0 5 5',
				width: 275,
				minSize: 100,
				maxSize: 500,
				items: [
					widgets.menuTree, 
					{
						id: 'details-panel',
						title: 'Details',
						region: 'center',
						bodyStyle: 'padding-bottom:15px;background:#eee;',
						autoScroll: true,
						html: 'When you select a layout from the tree'
					}
				]
			}, 
			{
				id: 'content-panel',
				region: 'center',
				layout: 'card',
				margins: '2 5 5 0',
				activeItem: 0,
				border: false
			}
        ],
		renderTo: document.body
    });
	
	
});