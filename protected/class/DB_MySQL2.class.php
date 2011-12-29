<?php
/**
	MySQL database wrapper v2.0beta
	Marenin Alexandr, Arseniev Alexey
	2011
	
	Класс не подходит для выборки большого количества данных,
	т.к. они сразу попадают в память PHP. Таким образом, не желательно 
	использование класса для выборок более чем 1000 строк за раз.
*/

class DB_MySQL2 implements ISQL_DB{
	
	
	protected 			$connection		= null;
	protected 			$connected		= false;	// Is DB connection set
	protected 			$db_id			= null;		// DB connection identifier
	protected			$query_id		= null;		// Query identifier
	protected static	$query_count	= 0;		// Global number of queries
	protected static	$query_time		= 0;		// All queries execution time
	protected			$last_query_time= 0;		// Last query time
	protected			$memcache		= null;		// Link to Memcache
	protected			$mmc_prefix		= 'qc_';	// Prefix to memchace query names
	protected			$query_data		= array();	// Last query data
	protected			$last_query		= '';		// Last SQL query (plain text)
	protected			$trans_started	= false;	// Whether runned $this->start whithout $this->commit
	private				$timer			= 0;
	private				$fetch_iterator	= 0;
	private				$current_fetched_query = '';
	
	
	// MAGIC METHODS
	public function __construct( $host, $user, $pass, $name ){
		$this->connection = array(
			'host' => $host,
			'user' => $user,
			'pass' => $pass,
			'name' => $name
		);
		// $this->connect( $host, $user, $pass, $name );
	}

	public function __destruct(){
		$this->close();
	}
	// MAGIC METHODS
	
	
	
	// Open DB connection
	public function connect( $host, $user, $pass, $name ){
		if ( $this->connected )
			$this->close();
		if ( !$this->db_id = @mysqli_connect( $host, $user, $pass, $name ) )
			throw new SQLException( 'Ошибка при подключении к БД' );
		$this->connected = true;
		$this->query( "SET NAMES 'UTF8'" );
	}

	private function _connect(){
		$this->connect(
			$this->connection['host'],
			$this->connection['user'],
			$this->connection['pass'],
			$this->connection['name']
		);
	}
	
	
	
	// DB close connection
	protected function close(){
		if ( $this->connected )
			mysqli_close( $this->db_id );
		$this->connected = false;
	}
	
	
	
	//
	// TRANSACTIONS
	//
	public function start(){
		$this->query('START TRANSACTION');
		$this->trans_started = true;
	}
	public function commit(){
		$this->query('COMMIT');
		$this->trans_started = false;
	}
	public function rollback(){
		$this->query('ROLLBACK');
		$this->trans_started = false;
	}
	public function is_started(){
		return $this->trans_started;
	}
	
	
	
	// Simple query without caching
	// @return все данные запроса( если SELECT )
	public function query( $query ){
		if ( !$this->connected )
			$this->_connect();

		$this->start_query();
		
		$this->last_query = $query;
		$this->query_id = mysqli_query( $this->db_id, $query );
		if ( !$this->query_id )
			throw new SQLException( 'Ошибка &lt;'. mysqli_error( $this->db_id ).'&gt; при выполнениии запроса &lt;'.$query.'&gt; ');
		// преобразуем результат в массив
		$this->fetch_all();
		
		$this->end_query();
		return $this->get_all();
	}
	
	
	
	// Метод выполняет запрос и возвращает одну строку результата
	// Если метод повторно вызывается с тем же запросом, то возвращается вторая строка и т.д.
	// Т.е. можно использовать следующую конструкцию while( $db->fetch_query($query) ){...}
	public function fetch_query( $query ){
		// $qkey = md5( $query );
		// if ( $this->current_fetched_query === $qkey )
			// return $this->fetch();
		
		// $this->current_fetched_query = $qkey;
		$this->query( $query );
		return $this->fetch();
	}
	
	
	
	// Метод выполняет запрос и возвращает одну строку результата
	// Если метод повторно вызывается с тем же запросом, то возвращается вторая строка и т.д.
	// Т.е. можно использовать следующую конструкцию while( $db->fetch_cached_query($query, $ttl) ){...}
	public function fetch_cached_query( $query, $ttl ){
		// $qkey = md5( $query );
		// if ( $this->current_fetched_query === $qkey )
			// return $this->fetch();
			
		// $this->current_fetched_query = $qkey;
		$this->cached_query( $query, $ttl );
		return $this->fetch();
	}
	
	
	
	// Query with caching
	// @param $query - query
	// @param $ttl - time to query live
	// @return если SELECT, то все данные запроса. Иначе хм...
	public function cached_query( $query, $ttl = 60 ){
		$this->start_query();
		
		// Получаем ключ запроса
		$query_key = md5( $query );
		
		if ( !$this->memcache )
			$this->memcache = Cache::getInstance();	
		
		// Проверяем есть ли запрос в кеше
		$res = $this->memcache->get( $this->mmc_prefix . $query_key );
		if ( $res ){
			$this->query_data = unserialize( $res );
			$this->end_query();
			return $this->query_data;
		}

		if ( !$this->connected )
			$this->_connect();
		$this->last_query = $query;
		// отправляем запрос в MySQL
		$this->query_id = mysqli_query( $this->db_id, $query );
		if ( !$this->query_id )
			throw new SQLException( '123123Ошибка &lt;'. mysqli_error( $this->db_id ).'&gt; при выполнениии запроса &lt;'.$query.'&gt; ');
		
		// преобразуем результат в массив
		$this->fetch_all();
		
		// Кешируем запрос
		$this->memcache->set( $this->mmc_prefix . $query_key, serialize( $this->query_data ), false, $ttl );
		
		$this->end_query();
		return $this->get_all();
	}
	
	
	
	// Обнуляет ранее кэшированный запрос
	public function reset_cached_query( $query, $time = 0 ){
		if ( !$this->memcache )
			$this->memcache = Cache::getInstance();	
		
		// Получаем ключ запроса
		$query_key = md5( $query );
		
		$this->memcache->delete( $this->mmc_prefix . $query_key, $time );
	}
	
	
	
	//
	protected function insert_replace_query( $type, $table, $data ){
		$data = unserialize(serialize($data)); // break references within array
		$keys_line   = '';
		$values_line = '';
		foreach ( $data as $key => $value ){
			$keys_line		.= '`'. $key .'`,';
			$values_line	.= '"'. $this->safe( $value ) .'",';
		}
		$keys_line   = substr( $keys_line, 0, strlen($keys_line) - 1 );
		$values_line = substr( $values_line, 0, strlen($values_line) - 1 );
		
		$table = (string) $table;
		$query = "$type INTO $table ($keys_line) VALUES ($values_line)";
		$this->query( $query );
		return $this->get_insert_id();
	}
	
	
	
	// Insert query
	// @return last insert id
	public function insert( $table, array $data ){
		return $this->insert_replace_query( 'INSERT', $table, $data );
	}

	
	
	// Replace query
	// @return last insert id
	public function replace( $table, array $data ){
		return $this->insert_replace_query( 'REPLACE', $table, $data );
	}

	
	
	// Преобразует все данные запроса в массив
	protected function fetch_all(){
		if ( !is_bool($this->query_id) ){
			$data = array();
			while( $row = mysqli_fetch_assoc( $this->query_id ) )
				$data[] = $row;
			unset( $this->query_data );
			$this->query_data = $data;
		}
	}
	
	
	
	// Возвращает ассоциативный массив со
	// строкой-результатом от предыдущего запроса
	public function fetch(){
		return	isset($this->query_data[$this->fetch_iterator]) ? 
				$this->query_data[$this->fetch_iterator ++] : false;
	}
	
	
	
	// Возвращает значение поля с именем $cell_name
	// или false, если данные закончились
	public function fetch_cell( $cell_name ){
		$cell_name = (string) $cell_name;
		$res = $this->fetch();
		if ( $res !== false )
			return isset($res[$cell_name]) ? $res[$cell_name] : false;
		else 
			return false;
	}
	
	
	
	// Возвращает двумерный массив - результат последнего запроса
	public function get_all(){
		return $this->query_data;
	}
	
	
	
	// возвращает идентификатор последней вставленной записи в БД
	// (значение автоинкрементного поля)
	public function get_insert_id(){
		return mysqli_insert_id( $this->db_id );
	}
	
	
	
	// return last query execution time
	public function get_last_query_time(){
		return $this->last_query_time;
	}
	
	
	
	// return all query execution time
	public function get_time(){
		return round(static::$query_time, 4);
	}
	
	
	
	// Возвращает количество измененных строк  
	// последним INSERT, UPDATE, REPLACE или DELETE запросом
	public function get_affected_rows(){
		return mysqli_affected_rows( $this->db_id );
	}
	
	
	
	// Возвращает количество строк затронутых 
	// последним SELECT или SHOW запросом
	public function get_num_rows(){
		return isset($this->query_id) ? mysqli_num_rows( $this->query_id ) : 0;
	}
	
	
	
	// return last error occured
	public function get_error(){
		return mysqli_error( $this->db_id );
	}
	
	
	
	// return queries count
	public function get_query_count(){
		return static::$query_count;
	}
	
	
	
	// return last query
	public function get_last_query(){
		return $this->last_query;
	}
	
	
	
	// Очистка строки для добавления в БД
	public function safe( $value ){
		if ( !$this->connected )
			$this->_connect();
        if ( is_string($value) ){
			if ( get_magic_quotes_gpc() ) 
				$value = stripslashes( $value );
			$value = mysqli_real_escape_string( $this->db_id, $value );
		}
		
        return $value;
	}
	
	
	
	// SERVICE FUNCTIONS (timing and counting)
	protected function start_query(){
		$this->timer = microtime(true);
		$this->fetch_iterator = 0;
	}
	protected function end_query(){
		static::$query_count ++;
		static::$query_time += microtime(true) - $this->timer;
		$this->last_query_time = microtime(true) - $this->timer;
	}
	
}