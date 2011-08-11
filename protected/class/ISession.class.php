<?php

//Интерфейс постоянного хранилища данных
interface ISession{
	
	public function __construct( $sid, $renew );
	public function __set( $key, $value );
	public function __get( $key );
	public function __isset( $key );
	public function __unset( $key );
	public function __toString();
	public function __destruct();
	
	//установка всех данных сессии
	public function set_all( $value );
	
	//удаление всей сессии
	public function unset_all();
	
}

?>