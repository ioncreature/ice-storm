<?
# Работа с Memcached

class Cache {

	private $connected = false;

	public $mmc_id = false;
	public $mmc_num = 0;
	public $mmc_time = 0;
	
	
	/**
    * Получение всегда одного и того же экземпляра класа (singleton)
    * @return object
    */
	private static $instance = null;
    public static function getInstance() {
        if( self::$instance == null )
            self::$instance = new self;
       
        return self::$instance;
    }

	
	// Соединение с memcached
	private function connect() {
		if ( !$this->mmc_id = @memcache_pconnect(MMC_HOST, MMC_PORT) )
			throw new CacheException( 'Невозможно установить подключение с сервером memcached: '. $e->getMessage() );

		$this->connected = true;
		return true;
	}
	
	
	public function get( $key ) {
		$this_time = microtime(1);
		if( !$this->connected ) 
			$this->connect();
		
		$status = memcache_get( $this->mmc_id, APP_NAME . $key );
		$this->mmc_time +=  microtime(1) - $this_time;
		$this->mmc_num++;
		
		return $status;
	}
	
	
	/**
	*	@param $key		- ключ
	*	@param $val		- значение
	*	@param $flag	- Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib). 
	*	@param $expire	- Expiration time of the item. От 0 и до 2592000 (30 дней)
	*/
	public function set( $key, $val, $flag, $expire ){
		$this_time = microtime(1);
		
		if( !$this->connected ) 
			$this->connect();
		

		$status = memcache_replace( $this->mmc_id, APP_NAME . $key, $val, $flag, $expire ); 
		if( $status == false ) { 
			$status = memcache_set( $this->mmc_id, APP_NAME . $key, $val, $flag, $expire ); 
		} 		
		
		$this->mmc_time +=  microtime(1) - $this_time;
		$this->mmc_num++;
		
		return $status;
	}
	
	
	/**
	*	Удаляет данные из базы
	*	@param $key		- ключ
	*	@param $time	- The amount of time the server will wait to delete the item. 
	*/
	public function delete( $key, $time = 0 ) {
		if ( !$this->connected ) 
			$this->connect();
		return memcache_delete( $this->mmc_id, APP_NAME . $key, $time );
	}	
}

?>