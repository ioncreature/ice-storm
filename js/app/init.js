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
	'dojox/charting/widget/Chart2D',
	'dojox/charting/themes/Claro',
	'dojox/validate/regexp',
	'dojo/domReady!'
], function( parser ){
	parser.parse( document.body );
	return { status: true };
});


