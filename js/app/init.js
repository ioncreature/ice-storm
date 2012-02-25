define([
	'dojo/parser',
	'dijit/layout/BorderContainer',
	'dijit/layout/TabContainer',
	'dijit/layout/ContentPane',
	'dijit/Tree',
	'app/widget/Form',
	'dijit/form/DateTextBox',
	'dijit/form/TextBox',
	'dijit/form/Button',
	'dijit/form/CheckBox',
	'dijit/form/ValidationTextBox',
	'dijit/form/Select',
	'dijit/form/FilteringSelect',
	'dojox/charting/themes/Claro',
	'dojox/charting/widget/Chart2D',
	'dojox/validate/regexp',
	'dojo/domReady!'
], function( parser ){
	console.log( 'init.js :: DOM Ready' );
	parser.parse( document.body );
	return { status: true };
});


