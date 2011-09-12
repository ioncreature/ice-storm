<?php
/*
	Class for output to user 
	includes templating and <header> processing
	Marenin Alex
	2011
*/

class Template {
	
	protected static $js = array();
	protected static $css = array();
	protected static $title = false;
	
	public static function add_js( $path ){
		static::$js[] = $path;
	}
	
	public static function add_css( $path ){
		static::$css[] = $path;
	}
	
	public static function set_title( $title ){
		static::$title = $title;
	}
	
	public static function top(){
		$styles = '';
		foreach ( static::$css as $css )
			$styles .= "\t<link rel='stylesheet' href='$css' />\n";
		
		$scripts = '';
		foreach ( static::$js as $js )
			$scripts .= "\t<script type='text/javascript' src='$js'></script>\n";
		
		$title = static::$title;
		include TEMPLATES_PATH. 'top.tpl.php';
	}
	
	public static function bottom(){
		static::show( 'bottom.tpl.php' );
	}
	
	// method output a template
	public static function show( $path, $params = false ){
		include TEMPLATES_PATH. $path;
	}
}
?>