<?php
/**
 * @author Marenin Alex
 *         January 2012
 */

namespace Helper;

class Html {
	public static function encode_attr( $text ){
		return htmlspecialchars( $text, ENT_QUOTES, APP_CHARSET );
	}

	public static function encode( $text ){
		return htmlspecialchars( $text, ENT_COMPAT, APP_CHARSET );
	}
}
