<?php
/**
 * Класс для описания модели
 * @author Marenin Alex
 * November 2011
 */

namespace Model;

/**
 * Класс модели. Моя реализация Active Record
 * TODO: добавить Class Table Inheritance
 */
abstract class AbstractModel implements \I\Exportable {

	/**
	 * List of fields like: array(
	 * 		'id',
	 * 		'name' => 'default value',
	 * 		'field_name' => array(
	 * 			'foreign_key' => 'foreign_field_name',
	 * 			'model' => '\Model\ClassName',
	 * 			'default' => 0,
	 * 			'type' => 'int'
	 * 		)
	 *	)
	 * @var array
	 */
	protected static $fields = array();
	protected static $table = '';
	protected static $primary_key = 'id';


	// inner params
	protected
		$data = array(),
		$orig_data = array(),
		$models = array(),
		$modified = false,
		$exists = false;

	/**
	 * @var \I\SqlDb
	 */
	protected $db;


	public function __construct( $object_id = false ){
		// Парсим поля
		foreach ( static::$fields as $key => $val ){
			$field = is_int($key) ? $val : $key;

			// 1. Простое поле
			if ( is_int($key) )
				$default = null;

			// 2. Поле с указанием дефолтного значения
			elseif ( is_string($key) and !is_array($val) )
				$default = $val;

			// 3. Поле с указанием списка параметров
			else {
				$default = isset($val['default']) ? $val['default'] : false;
				if ( isset($val['model']) ){
					$class_name = $this->parse_class_name( $val['model'] );
					$this->models[$class_name] = array(
						'fk' => $field,
						'model' => $val['model']
					);
				}
				// TODO: добавить обработку типов полей
			}

			$this->orig_data[$field] = $default;
		}
		if ( $object_id !== false )
			$this->get_by_id( $object_id );
	}


	private function parse_class_name( $name ){
		$chunks = explode( '\\', $name );
		return array_pop( $chunks );
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
			'fields', 'primary_key', 'models'
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
		$this->orig_data = $this->db->fetch_query('
			SELECT * FROM `'. static::$table .'` WHERE `'. static::$primary_key ."` = '$object_id' LIMIT 1
		");
		if ( $this->orig_data )
			$this->exists = true;

		return $this->export_array();
	}


	// Сохраняет изменения в БД
	public function save(){
		// сохраняем изменения в связанных моделях
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
			$data = array_diff_key( $this->data, array(static::$primary_key => true) );
			$this->data[ static::$primary_key ] = $this->db->insert( static::$table, $data );
			$this->exists = true;
			return $this->data[ static::$primary_key ];
		}

		// Обновляем существующую запись
		$set = '';
		foreach( $this->data as $key => $value )
			if ( $this->data[$key] !== $this->orig_data[$key] and $key !== static::$primary_key )
				$set .= "`$key` = '". $this->db->safe( $this->data[$key] ) ."',";
		$set = substr( $set, 0, -1 );

		if ( $set )
			$this->db->query("
				SET $set
				UPDATE `". static::$table ."`
				WHERE `". static::$primary_key ."` = '". $this->db->safe( $this->orig_data[static::$primary_key] ) ."'
			");
		return $this->orig_data[ static::$primary_key ];
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
		return $this;
	}


	/**
	 * Удаляет изменения в модели.
	 * @return \Model\AbstractModel
	 */
	public function reset(){
		$this->data = array();
		return $this;
	}


	public function apply( array $data ){
		// TODO: добавить валидацию полей
		foreach ( $data as $field => $value )
			$this->$field = $value; // use of magic __get
		return $this;
	}


	/**
	 * Reloads data from database
	 * @return \Model\AbstractModel
	 */
	public function update(){
		$this->get_by_id( $this->{static::$primary_key} );
		return $this;
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
		$class_name = $this->parse_class_name( $name );

		if ( isset($this->models[$class_name]) ){
			$m =& $this->models[$name];
			if ( !isset($m['instance']) ){
//				var_dump($m['model']);
				$m['instance'] = new $m['model']( $this->{$m['fk']} );
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
		foreach ( static::$fields as $k => $v ){
			$field = is_array($v) ? $k : $v;
			$default = is_array($v) ? (isset($v['default']) ? $v['default'] : null) : null;
			$out[$field] = isset( $array[$key_prefix.$field] ) ? $array[$key_prefix . $field] : $default;
		}
		return $out;
	}
}
