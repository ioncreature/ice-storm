<?php

namespace I;

interface SqlDb {
	
	/**
	 * Инициализирует класс
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 */
	public function __construct( $host, $user, $pass, $name );

	/**
	 * Открывает соединение с БД. Если соединение уже открыто,
	 * то закрывает существующее и открывает новое)
	 * @abstract
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 */
	public function connect( $host, $user, $pass, $name );

	/**
	 * Запрос без кеширования
	 * Возвращает все данные запроса( если SELECT )
	 * @abstract
	 * @param string $query
	 */
	public function query( $query );

	/**
	 * Запрос с кэшированием
	 * Возвращает все данные запроса( если SELECT )
	 * @abstract
	 * @param $query
	 * @param int $ttl
	 */
	public function cached_query( $query, $ttl = 120 );

	/**
	 * Выполняет добавление строки в БД
	 * @abstract
	 * @param $table
	 * @param array $data
	 */
	public function insert( $table, array $data );
	
	// Удаляет данные запроса из кэша
	public function reset_cached_query( $query, $time );
	
	
	// Возвращает ассоциативный массив со 
	// строкой-результатом от предыдущего запроса
	public function fetch();


	public function fetch_query();


	// Возвращает двумерный массив - результат последнего запроса
	public function get_all();
	
	
	// возвращает идентификатор последней вставленной записи в БД
	// (значение автоинкрементного поля)
	public function get_insert_id();
	
	
	// Возвращает количество строк затронутых 
	// последним SELECT или SHOW запросом
	public function get_num_rows();
	
	
	// Возвращает количество измененных строк  
	// последним INSERT, UPDATE, REPLACE или DELETE запросом
	public function get_affected_rows();
	
	
	// return last query execution time
	public function get_last_query_time();
	
	
	// return all query execution time
	public function get_time();
	
	
	// return last error occured
	public function get_error();
	
	
	// Очистка строки для добавления в БД
	public function safe( $value );
}

?>