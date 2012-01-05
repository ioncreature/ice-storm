<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace View;
use View\AbstractView as View;

class Json extends View {

	public function render(){
		return json_encode( $this->view_data );
	}


	public function render_not_found(){
		return json_encode( array(
			'status' => false,
			'code' => 404,
			'msg' => 'Not found'
		));
	}


	public function render_access_denied(){
		return json_encode( array(
			'status' => false,
			'code' => 403,
			'msg' => 'Forbidden'
		));
	}


	public function render_error(){
		return json_encode( array(
			'status' => false,
			'code' => 500,
			'msg' => 'Internal Server Error'
		));
	}
}
