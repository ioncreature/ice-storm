<?php
/**
 * 
 * @author Marenin Alex
 * November 2011
 */

// humans autocomplete
if ( $r->equal('service/humans/get/autocomplete') and isset($r->name) and Auth::is_logged() ){
	try {
		if ( mb_strlen($r->name) < 3 )
			throw new Exception( 'Name is too short' );
		$name = $db->safe( $r->name );
		$db = Fabric::get( 'db' );
		$res = $db->cached_query( "
			SELECT
				id, CONCAT( last_name, ' ', first_name, ' ', middle_name) as name
			FROM
				org_humans
			WHERE
				last_name LIKE '$name%'
			LIMIT 10
		", 300 );
		die( json_encode( array(
			'status' => true,
			'data' => $res
		)));
	}
	catch ( Exception $e ){
		die( json_encode(array('status' => false)) );
	}
}

elseif ( $r->equal('service/humans/get/::int') and Auth::is_logged() ){
	die( json_encode( array(
		'status' => false
	)));
}

