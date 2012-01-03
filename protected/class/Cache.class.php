<?php
/**
 * Memcache/Memcached simple wrapper
 * @author Marenin Alex
 * January 2012
 */

/**
 * Простой класс для работы с Memcache/Memcached
 */
class Cache {

	protected $mmc = null;
	protected $engine = MMC_ENGINE;
	protected $connected = false;
	protected $last_status = false;

	public $compress = false;
	public $query_count = 0;
	public $exec_time = 0;


	// Singleton
	private static $instance = null;
    public static function get_instance() {
        if( self::$instance == null )
            self::$instance = new self;

        return self::$instance;
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
				$this->mmc->addServer( MMC_HOST, MMC_PORT );

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
	public function get( $key ) {
		$start = microtime( true );
		if ( !$this->connected )
			$this->connect();
		$res = $this->mmc->get( $key );

		$this->last_status = !!$res;
		$this->exec_time +=  microtime( true ) - $start;
		$this->query_count ++;
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
		$start = microtime( true );
		if ( !$this->connected )
			$this->connect();

		if ( $this->engine === 'Memcache' )
			$status = $this->mmc->set( $key, $val, $this->compress, $expire );
		else
			$status = $this->mmc->set( $key, $val, $expire );

		$this->last_status = $status;
		$this->exec_time +=  microtime( true ) - $start;
		$this->query_count ++;
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
	public function delete( $key, $time = 0 ) {
		$start = microtime( true );
		if ( !$this->connected )
			$this->connect();

		$status = $this->mmc->delete( $key, $time );

		$this->last_status = $status;
		$this->exec_time +=  microtime( true ) - $start;
		$this->query_count ++;
		return $status;
	}



	public function replace( $key, $val, $expire ){
		$start = microtime( true );
		if ( !$this->connected )
			$this->connect();

		if ( $this->engine === 'Memcache' )
			$status = $this->mmc->replace( $key, $val, $this->compress, $expire );
		else
			$status = $this->mmc->replace( $key, $val, $expire );

		$this->last_status = $status;
		$this->exec_time +=  microtime( true ) - $start;
		$this->query_count ++;
		return $status;
	}
}
