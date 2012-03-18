<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace View;
use View\AbstractView as View,
	Response\AbstractResponse as Response;

class Json extends View {

	public function render(){
		return json_encode( $this->view_data );
	}


	public function render_not_found(){
		return json_encode( array(
			'status' => false,
			'code' => Response::STATUS_NOT_FOUND,
			'msg' => 'Not found'
		));
	}


	public function render_access_denied(){
		return json_encode( array(
			'status' => false,
			'code' => Response::STATUS_FORBIDDEN,
			'msg' => 'Forbidden'
		));
	}


	public function render_error(){
		return json_encode( array(
			'status' => false,
			'code' => Response::STATUS_ERROR,
			'msg' => 'Internal Server Error'
		));
	}


	public function render_bad_request(){
		return json_encode( array(
			'status' => false,
			'code' => Response::STATUS_BAD_REQUEST,
			'msg' => 'Bad Request'
		));
	}

}
