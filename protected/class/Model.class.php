<?php
/*
	Класс для описания модели
	Marenin Alex
	2011
*/

abstract class Model{

	protected $table = '';
	protected $fields = array();
	protected $model_name = 'Model';
	protected $primary_key = 'id';
	
	protected $data = array();
	protected $orig_data = null;
	protected $modified = false;
	protected $new = true;
	
	protected $db = null;
	
	
	public __construct( $object_id = false ){
		if ( $object_id !== false ){
			$this->get_by_id( $object_id );
		}
		else {
			foreach ( $this->fields as $f )
				$this->orig_data[$f] = null;
			$this->data = $this->orig_data;
		}
	}
	
	protected db_connect(){
		if ( $this->db === null )
			$this->db = Fabric::get( 'db' );
	}
	
	public __set( $key, $value ){
		if ( isset($this->data[$key]) and ($this->data[$key] !== $value) )
			$this->modified = true;
		$this->data[$key] = $value;
	}
	
	
	public __get( $key ){
		return isset($this->data[$key]) ? $this->data[$key] : false;
	}
	
	
	public __toString(){
		if ( $this->new )
			return 'Empty '. $this->model_name;
		return var_export( $this->data, true );
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
			$this->new = false;
			return true; 
		}
		
		return false;
	}
	
	
	// Сохраняет изменения в БД
	public function save(){
		if ( !$this->modified )
			return false;
		
		$this->db_connect();
		
		// Добавляем новую запись
		if ( $this->new ){
			$this->data[ $this->primary_key ] = $this->db->insert( $this->table, $this->data );
			return $this->data[ $this->primary_key ];
		}
		
		// Обновляем существующую запись
		$set = '';
		foreach( $this->data as $key => $value )
			if ( $this->data[$key] !== $this->orig_data[$key] )
				$set .= "`$key` = '". $this->db->safe( $this->data[$key] ) ."',";
		$set = substr( $set, 0, -1 );
		
		$this->db->query("
			UPDATE {$this->table_name} 
			SET $set 
			WHERE `{$this->primary_key}` = '". $this->db->safe( $this->orig_data[ $this->primary_key ]) ."'
		");
		return $this->orig_data[ $this->primary_key ];
	}

}



?>