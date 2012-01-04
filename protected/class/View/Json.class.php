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

	public function show_404(){
		return json_encode( array(
			'status' => false,
			'code' => 404,
			'msg' => 'Not found'
		));
	}
}
