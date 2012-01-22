<?php
/**
 * Класс для описания модели
 * @author Marenin Alex
 * November 2011
 */

namespace Model;

abstract class AbstractModel {

	// ----------
	// fields for redefining in descendant classes
	
	protected $table = '';
	protected $primary_key = 'id';

	/**
	 * List of fields like: array(
	 * 		'id',
	 * 		'name' => 'default value',
	 * 		'field_name' => array(
	 * 			'foreign_key' => 'foreign_field_name',
	 * 			'model' => 'ModelClassName',
	 * 			'default' => 0,
	 * 			'type' => 'int'
	 * 		)
	 *	)
	 * @var array
	 */
	protected $fields = array();

	// fields for redefining in descendant classes
	// ----------

	
	protected $data = array();
	protected $orig_data = array();
	protected $models = array();
	protected $modified = false;
	protected $exists = false;
	protected $db = null;


	public function __construct( $object_id = false ){
		foreach ( $this->fields as $key => $val ){
			$field = is_int($key) ? $val : $key;
			// 1. Простое поле
			// 2. Поле с указанием дефолтного значения
			if ( !is_array($val) )
				$default = is_int($key) ? false : $val;

			// 3. Поле с указанием списка параметров
			else {
				$default = isset($val['default']) ? $val['default'] : false;
				if ( isset($val['model']) )
					$this->models[$val['model']] = array(
						'fk' => $field,
						'namespace' => isset($val['namespace']) ? $val['namespace'] : __NAMESPACE__
					);
				// TODO: добавить обработку типов полей
			}

			$this->orig_data[$field] = $default;
		}
		if ( $object_id !== false )
			$this->get_by_id( $object_id );
	}


	protected function db_connect(){
		if ( !$this->db )
			$this->db = \Fabric::get( 'db' );
	}


	/**
	 * Mega setter
	 * @param $key
	 * @param $value
	 * @return void
	 */
	public function __set( $key, $value ){
		// TODO: добавить проверку типов
		if ( isset($this->orig_data[$key]) and $this->orig_data[$key] !== $value )
			$this->modified = true;
		$this->data[$key] = $value;
	}


	/**
	 * Mega getter. Returns field value or some model instance
	 * @param $key
	 * @return bool|string|\Model\AbstractModel
	 */
	public function __get( $key ){
		if ( isset($this->data[$key]) )
			return $this->data[$key];
		elseif ( isset($this->orig_data[$key]) )
			return $this->orig_data[$key];
		elseif ( $this->get_model($key) )
			return $this->get_model( $key );
		else
			return false;
	}


	public function __toString(){
		return !$this->exists ?
			'Empty model ('.__CLASS__.')' : var_export( $this->data, true );
	}


	public function __sleep(){
		return array(
			'data', 'orig_data', 'exists', 'table',
			'fields', 'model_name', 'primary_key', 'models'
		);
	}


	public function exists(){
		return $this->exists;
	}


	// load model by id
	public function get_by_id( $object_id ){
		$object_id = (int) $object_id;
		$this->db_connect();
		// TODO: сделать загрузку полей только из списка $this->fields
		$this->orig_data = $this->db->fetch_query("
			SELECT * FROM {$this->table} WHERE `{$this->primary_key}` = '$object_id' LIMIT 1
		");
		if ( $this->orig_data )
			$this->exists = true;

		return $this->export_array();
	}


	// Сохраняет изменения в БД
	public function save(){
		// сохраняем измнения в связанных моделях
		foreach ( $this->models as $name => &$m )
			if ( isset($m['instance']) )
				$m['instance']->save();

		// сохраняем текущую модель
		if ( !$this->modified )
			return false;
		$this->db_connect();

		$this->before_save();
		// Добавляем новую запись
		if ( !$this->exists ){
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
				WHERE `{$this->primary_key}` = '". $this->db->safe( $this->orig_data[$this->primary_key] ) ."'
			");
		return $this->orig_data[ $this->primary_key ];
	}


	/**
	 * @param $data
	 * @return bool
	 */
	protected function load( $data ){
		$this->exists = true;
		$this->reset();
		$this->data = array();
		$this->orig_data = $data;
		return true;
	}


	/**
	 * Удаляет изменения в модели.
	 */
	public function reset(){
		unset( $this->data );
		$this->data = array();
	}


	public function apply( array $data ){
		// TODO: добавить валидацию полей
		foreach ( $data as $field => $value )
			$this->$field = $value;
		return $this;
	}


	/**
	 * Reloads data from database
	 * @return void
	 */
	public function update(){
		$this->get_by_id( $this->{$this->primary_key} );
	}


	/**
	 * Callback fired before calling Model->save()
	 */
	protected function before_save(){
		// redefine in descendants
	}


	public function is_changed( $key ){
		return isset($this->data[$key]) ? $this->data[$key] !== $this->orig_data[$key] : false;
	}


	public function get_model( $name ){
		if ( isset($this->models[$name]) ){
			$m =& $this->models[$name];
			if ( !isset($m['instance']) ){
				$class_name = ('\\'. $m['namespace'] ? $m['namespace'] .'\\' : '') . $name;
				$m['instance'] = new $class_name( $this->{$m['fk']} );
			}
			return isset($m['instance']) ? $m['instance'] : false;
		}
		else return false;
	}


	/**
	 * Returns model fields values as array( <field_name> => <field_value> )
	 * @return array
	 */
	public function export_array(){
		return $this->data + $this->orig_data;
	}


	/**
	 * Filters input array
	 * @param $array
	 * @param null|string $key_prefix
	 * @return array
	 */
	public function filter( $array, $key_prefix = null ){
		$out = array();
		foreach ( $this->fields as $k => $v ){
			$field = is_array($v) ? $k : $v;
			$default = is_array($v) ? (isset($v['default']) ? $v['default'] : null) : null;
			$out[$field] = isset( $array[$key_prefix.$field] ) ? $array[$key_prefix . $field] : $default;
		}
		return $out;
	}
}
