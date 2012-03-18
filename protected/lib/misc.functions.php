<?php
// форматирование денег
function formatnum($num, $type = 'money') {
	switch($type) {
		case "money":	
			$num = (float) $num;
			 if ( $num >= 1 ){
				$num = number_format($num, 2, ',', ' ');
				$num = $num ." руб.";
			}
			else 
				$num = round($num * 100) ." коп.";
			return $num;
			
		case "tenth":	return number_format( $num, 1, ',', ' ' );
		case "ceil":	return number_format( $num, 0, ',', ' ' );
		default:		return $num;
	}
}


function redirect( $path ){
	header( 'Location: '. $path );
	die;
}


// форматирование дат
function formatdate($date, $format = "datetime", $toint = true) {
	if ( $toint ) 
		$date = strtotime($date);
	
	$en_month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$ru_month = array('января','февраля','марта','апреля','мая','июня','июля',
				'августа','сентября','октября','ноября','декабря');
	
	switch ( $format ){
		case "datetime":
			return date("d.m.Y H:i:s", $date);
		case "dateMtime":
			return str_replace( $en_month, $ru_month, date("j M Y в H:i", $date) );
		case "dateM":
			return str_replace( $en_month, $ru_month, date("j M Y", $date));
	}
}



/**
* Multibyte safe version of trim()
* Always strips whitespace characters (those equal to \s)
*
* @author Peter Johnson
* @email phpnet@rcpt.at
* @param $string The string to trim
* @param $chars Optional list of chars to remove from the string ( as per trim() )
* @param $chars_array Optional array of preg_quote'd chars to be removed
* @return string
*/
function mb_trim( $string, $chars = "", $chars_array = array() ){
    for( $x=0; $x<iconv_strlen( $chars ); $x++ ) $chars_array[] = preg_quote( iconv_substr( $chars, $x, 1 ) );
    $encoded_char_list = implode( "|", array_merge( array( "\s","\t","\n","\r", "\0", "\x0B" ), $chars_array ) );

    $string = mb_ereg_replace( "^($encoded_char_list)*", "", $string );
    $string = mb_ereg_replace( "($encoded_char_list)*$", "", $string );
    return $string;
}



/**
 * строит дерево из списка
 * @param array $list
 * @param int $did
 * @param int $depth
 * @return array|bool
 */
function get_departments_tree( array &$list, $did = 0, $depth = 10 ){
	if ( empty($list) )
		return array();
	if ( $depth === 0 )
		return false;

	$childs = array();
	foreach ( $list as $k => $dep )
		if ( $dep['parent_id'] == $did ){
			$childs[] = $dep;
			unset( $list[$k] );
		}

	foreach ( $childs as $k => $c )
		$childs[$k]['children'] = get_departments_tree( $list, $c['id'], --$depth );

	return $childs;
}





function array_merge_recursive_distinct(){
	if ( func_num_args() < 2 ){
		trigger_error( __FUNCTION__ .' needs two or more array arguments', E_USER_WARNING );
		return;
	}

	$arrays = func_get_args();
	$merged = array();
	while ( $arrays ){
		$array = array_shift( $arrays );
		if ( !is_array($array) ){
			trigger_error( __FUNCTION__ .' encountered a non array argument', E_USER_WARNING );
			return;
		}
		if ( !$array )
			continue;
		foreach ( $array as $key => $value )
			if ( is_string($key) )
				if ( is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key]) )
					$merged[$key] = call_user_func( __FUNCTION__, $merged[$key], $value );
				else
					$merged[$key] = $value;
			else
				$merged[] = $value;
	}
	return $merged;
}

?>