<?php
/*
	Класс для работы с данными пользователя
	Marenin Alexandr
	2011
*/

// Класс для работы с юзером
class User extend Model{
	public function __construct( $user_id ){
		$this->db = Fabric::get( 'db' );
		
		if ( $param1 !== false ){
			$data = $this->is_registered( $param1, $param2 );
			if ( $data ){
				$this->data = $data;
				$this->is_registered = true;
			}
		}
	}
}
?>