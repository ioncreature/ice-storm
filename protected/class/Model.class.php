<?php
/**
 * Класс для описания модели
 * @author Marenin Alex
 * 2011
 */

abstract class Model{

	protected $table = '';
	protected $fields = array();
	protected $model_name = 'Model';
	protected $primary_key = 'id';
	
	protected $data = array();
	protected $orig_data = array();
	protected $modified = false;
	protected $exists = false;
	protected $db = null;
	
	public function __construct( $object_id = false ){
		if ( $object_id !== false )
			$this->get_by_id( $object_id );
		else {
			foreach ( $this->fields as $key => $val ){
				$field = is_int($key) ? $val : $key;
				$default = is_int($key) ? false : $val;
				
				$this->orig_data[$field] = $default;
			}
			$this->data = $this->orig_data;
		}
	}
	
	
	protected function db_connect(){
		if ( $this->db === null )
			$this->db = Fabric::get( 'db' );
	}
	
	
	public function __set( $key, $value ){
		if ( isset($this->data[$key]) and ($this->data[$key] !== $value) )
			$this->modified = true;
		$this->data[$key] = $value;
	}
	
	
	public function __get( $key ){
		return isset($this->data[$key]) ? $this->data[$key] : false;
	}
	
	
	public function __toString(){
		if ( ! $this->exists )
			return 'Empty '. $this->model_name;
		return var_export( $this->data, true );
	}
	
	
	public function __sleep(){
		return array( 'data', 'orig_data', 'exists', 'table', 'fields', 'model_name', 'primary_key' );
	}
	
	public function __wakeup(){
	}
	
	public function exists(){
		return $this->exists;
	}
	
	
	// load model by id
	public function get_by_id( $object_id ){
		$object_id = (int) $object_id;
		$this->db_connect();
		$this->orig_data = $this->db->fetch_query("
			SELECT * FROM {$this->table} WHERE id = '$object_id' LIMIT 1
		");
		if ( $this->orig_data ){
			$this->data = $this->orig_data;
			$this->exists = true;
			return true; 
		}
		
		return false;
	}
	
	
	// Сохраняет изменения в БД
	public function save(){
		if ( !$this->modified )
			return false;
		$this->db_connect();
		
		$this->before_save();
		// Добавляем новую запись
		if ( ! $this->exists ){
			$data = array_diff_key( $this->data, array($this->primary_key => true) );
			$this->data[ $this->primary_key ] = $this->db->insert( $this->table, $data );
			$this->exists = true;
			return $this->data[ $this->primary_key ];
		}
		
		// Обновляем существующую запись
		$set = '';
		foreach( $this->data as $key => $value )
			if ( $this->data[$key] !== $this->orig_data[$key] )
				$set .= "`$key` = '". $this->db->safe( $this->data[$key] ) ."',";
		$set = substr( $set, 0, -1 );
		
		if ( $set )
			$this->db->query("
				UPDATE {$this->table} 
				SET $set 
				WHERE `{$this->primary_key}` = '". $this->db->safe( $this->orig_data[ $this->primary_key ]) ."'
			");
		return $this->orig_data[ $this->primary_key ];
	}
	
	
	public function load( $data ){
		$this->exists = true;
		$this->data = $data;
		$this->orig_data = $data;
		return true;
	}
	
	
	protected function before_save(){
	}
	
	
	public function is_changed( $key ){
		return $this->data[$key] !== $this->orig_data[$key];
	}
}
?>