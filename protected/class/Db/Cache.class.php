<?php
/**
 * Memcache/Memcached simple wrapper
 * @author Marenin Alex
 * January 2012
 */


namespace Db;

// TODO: добавить методы add и cas

/**
 * Простой класс для работы с Memcache/Memcached
 */
class Cache {

	const
		KEY_TTL = 600; // 10 min

	protected
		$mmc = null,
		$engine,
		$connected = false,
		$last_result = false,
		$host,
		$port;


	public
		$compress = false,
		$query_count = 0,
		$exec_time = 0,
		$start = 0;


	/**
	 * @param string $host
	 * @param int $port
	 */
	public function __construct( $host, $port, $engine ){
		$this->host = $host;
		$this->port = $port;
		$this->engine = mb_strtolower( $engine );
	}

	
	/**
	 * Соединение с memcached
	 * @return bool
	 * @throws CacheException
	 */
	protected function connect(){
		try {
			if ( !$this->connected ){
				$this->mmc = new $this->engine;
				$this->mmc->addServer( $this->host, $this->port );

				$this->connected = true;
			}
			return true;
		}
		catch ( Exception $e ){
			throw new CacheException( 'Cache::connect : unable to connect', 0, $e );
		}
	}


	/**
	 * Returns instance of Memcache or Memcached class
	 * @return Object
	 */
	public function get_mmc(){
		if ( !$this->connected )
			$this->connect();
		return $this->mmc;
	}


	public function is_memcache(){
		return $this->engine === 'memcache';
	}


	public function is_memcached(){
		return $this->engine === 'memcached';
	}


	/**
	 * @param $key
	 * @return mixed
	 */
	public function get( $key ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();
		$res = $this->mmc->get( $key );

		$this->bm_end( $res );
		return $res;
	}
	

	/**
	 * Устанавливает в кеш данные
	 * @param string $key	- ключ
	 * @param mixed $val	- значение
	 * @param int $expire	- Expiration time of the item. От 0 и до 2592000 (30 дней)
	 * @return boolean
	 */
	public function set( $key, $val, $expire = KEY_TTL ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();

		if ( $this->is_memcache() )
			$status = $this->mmc->set( $key, $val, $this->compress, $expire );
		else
			$status = $this->mmc->set( $key, $val, $expire );

		$this->bm_end( $status );
		return $status;
	}


	/**
	 * Tells success status of previous operation
	 * @return bool
	 */
	public function is_success(){
		return $this->is_memcache() ? $this->last_result : $this->mmc->getResultCode() === 0;
	}


	/**
	 * Удаляет данные из кэша
	 * @param $key
	 * @param int $time - The amount of time the server will wait to delete the item.
	 * @return boolean
	 */
	public function delete( $key, $time = 0 ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();

		$status = $this->mmc->delete( $key, $time );

		$this->bm_end( $status );
		return $status;
	}


	/**
	 * @param string $key
	 * @param mixed $val
	 * @param int $expire
	 * @return mixed
	 */
	public function replace( $key, $val, $expire = KEY_TTL ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();

		if ( $this->is_memcache() )
			$status = $this->mmc->replace( $key, $val, $this->compress, $expire );
		else
			$status = $this->mmc->replace( $key, $val, $expire );

		$this->bm_end( $status );
		return $status;
	}


	/**
	 * Simple benchmark start
	 */
	private function bm_start(){
		$this->start = microtime( true );
	}

	/**
	 * Simple benchmark end
	 * @param mixed $status
	 */
	private function bm_end( $status ){
		$this->last_result = $status;
		$this->exec_time += microtime( true ) - $this->start;
		$this->query_count ++;
	}
}
