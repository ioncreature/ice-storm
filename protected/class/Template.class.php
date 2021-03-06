<?php
/**
 * Class for output to user
 * includes templating and <header> processing
 * @author Marenin Alex
 * 2011
 *
 * TODO: Add block content caching
 */
class Template {
	
	protected static $js = array();
	protected static $css = array();
	protected static $title = '';
	protected static $block = array();
	protected static $ob_block_name = false;
	protected static $output_started = false;


	/**
	 * Устанавливает или возвращает текущий html title
	 * @static
	 * @param string $title
	 * @return string
	 */
	public static function title( $title = false ){
		if ( $title )
			static::$title = $title;
		else
			return htmlspecialchars( static::$title ? static::$title : \Ice::config('app_title') );
	}


	/**
	 * @static Добавляет к текущему html title подзаголовок
	 * @param string $subtitle подзаголовок
	 * @return void
	 */
	public static function add_to_title( $subtitle ){
		if ( static::$title )
			static::$title .= '. '. $subtitle;
		else
			static::$title = $subtitle;
	}


	/**
	 * Добавляет в блок head js-скрипт
	 * @static
	 * @param string $path путь до файла с js-скриптом
	 * @param array $attributes дополнительные аттрибуты
	 * @return void
	 */
	public static function add_js( $path, array $attributes = array() ){
		if ( !isset($attributes['type']) )
			$attributes['type'] = 'text/javascript';
				$attr = static::attrs( $attributes );

		static::$js[] = array(
			'path' => $path,
			'attributes' => $attributes
		);

		static::add_to_block( 'scripts', "\t<script $attr src='$path'></script>\n" );
	}


	/**
	 * Добавляет в блок head js-скрипт
	 * @static
	 * @param string $path путь до файла со стилями
	 * @param array $attributes
	 * @return void
	 */
	public static function add_css( $path, array $attributes = array() ){
		if ( !isset($attributes['rel']) )
			$attributes['rel'] = 'stylesheet';
		if ( !isset($attributes['type']) )
			$attributes['type'] = 'text/css';
		$attr = static::attrs( $attributes );

		static::$css[] = array(
			'path' => $path,
			'attributes' => $attributes
		);

		static::add_to_block( 'styles', "\t<link href='$path' $attr/>\n" );
	}


	protected static function attrs( array $attributes = array() ){
		if ( !$attributes )
			return '';
		$out = '';
		foreach ( $attributes as $attr => $val ){
			$val = str_replace( "'", "\\'", $val );
			$out .= "$attr='$val' ";
		}
		return $out;
	}


	public static function top(){
		static::show( 'top' );
	}


	public static function bottom(){
		static::show( 'bottom' );
	}


	/**
	 * Renders and output all page if nothing is sent
	 * @static
	 * @return bool
	 */
	public static function output(){
		if ( static::$output_started )
			return false;
		static::ob_end();
		static::top();
		static::bottom();
	}


	/**
	 * method output a template
	 * @static
	 * @param $path
	 * @param bool $data
	 * @return void
	 */
	public static function show( $template_name, $data = array() ){
		static::$output_started = true;
		include TEMPLATES_PATH. $template_name .'.tpl.php';
	}


	/**
	 * @static
	 * @param $template_name
	 * @param $data
	 * @return string
	 */
	public static function tpl( $template_name, $data = array() ){
		ob_start();
		include TEMPLATES_PATH. $template_name .'.tpl.php';
		return ob_get_clean();
	}


	/**
	 * Outputs template into block
	 * @static
	 * @param $block_name
	 * @param $template_name
	 * @param array $data
	 * @return string
	 */
	public static function template_to_block( $block_name, $template_name, $data = array() ){
		Template::ob_to_block( $block_name );
		include TEMPLATES_PATH. $template_name .'.tpl.php';
		Template::ob_end();
		return static::block( $block_name );
	}


	public static function add_to_block( $name, $content ){
		if ( isset(static::$block[$name]) )
			static::$block[$name] .= $content;
		else
			static::$block[$name] = $content;
	}


	public static function reset_block( $name ){
		unset( static::$block[$name] );
	}
	

	public static function block( $name ){
		return isset(static::$block[$name]) ? static::$block[$name] : '';
	}


	public static function block_is_set( $name ){
		return isset( static::$block[$name] );
	}


	/**
	 * Start catching output
	 * @static
	 * @param $name
	 */
	public static function ob_to_block( $name ){
		if ( static::$ob_block_name )
			static::ob_end();

		static::$ob_block_name = $name;
		ob_start();
	}


	/**
	 * Stop catching output and save into block;
	 * @static
	 */
	public static function ob_end(){
		if ( static::$ob_block_name )
			static::add_to_block( static::$ob_block_name, ob_get_clean() );
		static::$ob_block_name = false;
	}
}
?>
