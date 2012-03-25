<?php
/**
 * Memcache/Memcached simple wrapper
 * @author Marenin Alex
 * January 2012
 */


namespace Db;

/**
 * Простой класс для работы с Memcache/Memcached
 */
class Cache {

	protected $mmc = null;
	protected $engine = MMC_ENGINE;
	protected $connected = false;
	protected $last_status = false;
	protected $host;
	protected $port;


	public $compress = false;
	public $query_count = 0;
	public $exec_time = 0;
	public $start = 0;


	/**
	 * @param string $host
	 * @param int $port
	 */
	public function __construct( $host, $port ){
		$this->host = $host;
		$this->port = $port;
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


	/**
	 * @param $key
	 * @return mixed
	 */
	public function get( $key ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();
		$res = $this->mmc->get( $key );

		$this->bm_end( $status );
		return $res;
	}
	

	/**
	 * Устанавливает в кеш данные
	 * @param string $key	- ключ
	 * @param mixed $val	- значение
	 * @param int $expire	- Expiration time of the item. От 0 и до 2592000 (30 дней)
	 * @return boolean
	 */
	public function set( $key, $val, $expire ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();

		if ( $this->engine === 'Memcache' )
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
		return $this->engine === 'Memcache' ? $this->last_status : $this->mmc->getResultCode() === 0;
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



	public function replace( $key, $val, $expire ){
		$this->bm_start();
		if ( !$this->connected )
			$this->connect();

		if ( $this->engine === 'Memcache' )
			$status = $this->mmc->replace( $key, $val, $this->compress, $expire );
		else
			$status = $this->mmc->replace( $key, $val, $expire );

		$this->bm_end( $status );
		return $status;
	}


	private function bm_start(){
		$this->start = microtime( true );
	}

	private function bm_end( $status ){
		$this->last_status = $status;
		$this->exec_time += microtime( true ) - $this->start;
		$this->query_count ++;
	}
}
