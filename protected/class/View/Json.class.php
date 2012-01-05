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
		header( "HTTP/1.1 404 Not Found" );
		return json_encode( array(
			'status' => false,
			'code' => 404,
			'msg' => 'Not found'
		));
	}


	public function render_access_denied(){
		header( "HTTP/1.1 403 Forbidden" );
		return json_encode( array(
			'status' => false,
			'code' => 403,
			'msg' => 'Forbidden'
		));
	}


	public function render_error(){
		header( "HTTP/1.1 500 Internal Server Error" );
		return json_encode( array(
			'status' => false,
			'code' => 500,
			'msg' => 'Internal Server Error'
		));
	}
}
