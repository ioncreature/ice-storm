Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'IceStorm': 'app'
    }
});

Ext.require('IceStorm.Application');

Ext.onReady(function() {
    Ext.create('IceStorm.Application');
});
