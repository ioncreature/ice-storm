<?php
/**
 * @author Marenin Alex
 *         December 2011
 */

namespace View;
use View\AbstractView;

class Json extends AbstractView {

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
}
