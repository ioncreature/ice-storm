<?php

// Хеш-функция
function mega_hash( $word ){
	return md5( md5( $word ) . 'salt' );
}


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


// форматирование дат
function formatdate($date, $format = "datetime", $toint = true) {
	if ( $toint ) 
		$date = strtotime($date);
		
	switch ( $format ){
		case "datetime":
			return date("d.m.Y H:i:s", $date);
			break;
		
		case "dateMtime":
			return str_replace(
			array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
			array('января','февраля','марта','апреля','мая','июня','июля',
				'августа','сентября','октября','ноября','декабря'),
				date("j M Y в H:i", $date));
				
		case "dateM":
			return str_replace(
			array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
			array('января','февраля','марта','апреля','мая','июня','июля',
				'августа','сентября','октября','ноября','декабря'),
				date("j M Y", $date));
		break;
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
?>