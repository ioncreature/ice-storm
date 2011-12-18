<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;
use View\AbstractView;

class JsonView extends AbstractView {
	public function render(){
		return json_encode( $this->view_data );
	}
}
