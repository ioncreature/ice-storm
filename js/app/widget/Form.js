/**
 * @author Marenin Alex
 * January 2012
 */

define(
	'app/widget/Form',
	['dojo/_base/declare', 'dijit/form/Form', 'dojo/window', 'dojo/_base/array' ],
	function( declare, Form, winUtils, array ){
	declare( 'app.widget.Form', Form, {
		validate: function( name ){
			// summary:
			//		returns if the form is valid - same as isValid - but
			//		provides a few additional (ui-specific) features.
			//		1 - it will highlight any sub-widgets that are not
			//			valid
			//		2 - it will call focus() on the first invalid
			//			sub-widget
			var didFocus = false;
			return array.every(array.map(this._getDescendantFormWidgets(), function(widget){
				if ( (name && widget.get('name') === name) || !name ){
					// Need to set this so that "required" widgets get their
					// state set.
					widget._hasBeenBlurred = true;
					var valid = widget.disabled || !widget.validate || widget.validate();
					if(!valid && !didFocus){
						// Set focus of the first non-valid widget
						winUtils.scrollIntoView(widget.containerNode || widget.domNode);
						widget.focus();
						didFocus = true;
					}
					return valid;
				}
				else return true;
			}), function(item){ return item; });
		}
	});
});