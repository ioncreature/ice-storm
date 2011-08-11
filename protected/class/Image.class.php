<?php
/**
	Image processing
	Uses GD2 extension
	Marenin Alex
	2011
*/
class Image {

	protected $img;					// image resource
	protected $ext = 'jpeg';		// тип картинки 'jpg', 'jpeg' or 'png'
	protected $settings = array(	// настройки по-умолчанию
		'font-size' => 14,			// размер шрифта 
		'color'		=> 'FFFFFF',	// цвет
		'angle'		=> 0			// угол наклона
	);
	
	
	
	// MAGIC METHODS -----------------------------------------------------
	public function __construct( $file, $extension = false ){
		$this->load( $file, $extension );
	}
	
	public function __destruct(){
		$this->free();
	}
	// MAGIC METHODS -----------------------------------------------------
	
	
	
	// Загружает картинку в объект
	// Если $extension не указано, то конструктор попытается сам определить тип файла
	// throws ImageException
	public function load( $file, $extension = false ){
		
		if ( is_resource($file) ){
			$this->img = $file;
			return true;
		}
		
		if ( $extension === false )
			$this->ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		else
			$this->ext = strtolower( $extension );

		switch ( $this->ext ){		
			case 'jpeg': 
			case 'jpg': 
				$this->img = @imagecreatefromjpeg( $file );
				break;
			
			case 'png': 
				$this->img = @imagecreatefrompng( $file );
				break;
			
			default:
				throw new ImageException( 'Unacceptable file extension. Must be jpg, jpeg or png' );
		}
		
		if ( !$this->img )
			throw new ImageException( 'Incorrect image file');
			
		return true;
	}
	
	
	
	// переустанавливает $this->img и корректно очищает память
	// $img - resource изображения
	protected function reset( $img ){
		$this->free();
		$this->img = $img;
	}
	
	
	
	// удаляет изображение
	public function free(){
		@imagedestroy( $this->img );
	}
	
	
	
	// Уменьшает размер изображения
	// Если изображение меньше заданных ширины и высоты, то уменьшение не происходит
	public function reduce( $width, $height, $save_proportions = true, $side = "max" ){
		
		$xratio = $this->getX() / $width;
		$yratio	= $this->getY() / $height;
		if ( $save_proportions ){
			if($side == "max")
				$ratio = max( 1, $xratio, $yratio );
			else 
				$ratio = min( 1, $xratio, $yratio );
				
			$nx = floor( $this->getX() / $ratio );
			$ny = floor( $this->getY() / $ratio );
		}
		else {
			$nx = floor( $this->getX() / max( 1, $xratio ) );
			$ny = floor( $this->getY() / max( 1, $yratio ) );
		}
		
		$img_new = ImageCreateTrueColor( $nx, $ny );
		$t = imagecopyresampled( 
				$img_new, $this->img, 
				0, 0, 0, 0, 
				$nx, $ny, $this->getX(), $this->getY()
		);
		
		if ( !$t )
			throw new ImageException( 'Error occured while reducing image(REDUCE method)' );
		
		$this->reset( $img_new );
		return true;
	}
	
	
	
	// Обрезка изображения
	// Если не заданы смещения по x и y, то вырезается из середины
	public function cut( $width, $height, $param1 = false , $param2 = false ){
		
		list( $dx, $dy ) = 
			$this->checkPositioning( $width, $height, $param1, $param2 );
			
		$img_new = imagecreatetruecolor( $width, $height );
		$t = imagecopyresampled( 
				$img_new, $this->img, 
				0, 0, $dx, $dy,
				$width, $height, $width, $height
		);
		
		if ( !$t )
			throw new ImageException( 'Error occured while cutting image(CUT method)' );
		
		$this->reset( $img_new );
		return true;
	}
	
	
	
	// Добавляет водяной знак-картинку
	// @param $img_res - путь до файла, либо image resource
	// @param $opacity - при 0 - невидимый, при 100 - непрозрачный
	// @param $param1 -
	// 		Если $param1 - строка то это позиционирование. Допустимы любые пары сочетаний
	//			'top', 'middle', 'bottom'  и 
	//			'left', 'center', 'right' 
	//			Примеры - 'top left', 'left middle', 'middle center' и т.д.
	// 		Если $param1 и $param2 - целые числа, то это смещение от верхнего левого края
	public function watermark( $img_res, $opacity = 100, $param1 = false, $param2 = false ){
		
		if ( is_string($img_res) or is_resource($img_res) )
			$img_source = new Image( $img_res );
		else
			throw new ImageException( "$img_res not string and not image resource" );
		
		$opacity = (int) $opacity;
		
		list( $x_off, $y_off ) = 
			$this->checkPositioning( $img_source->getX(), $img_source->getY(), $param1, $param2 );
		
		$status = @imagecopymerge(
				$this->img, $img_source->getImageResource(),
				$x_off, $y_off, 0, 0, 
				$img_source->getX(), $img_source->getX(), 
				$opacity 
		);
		return true;
	}
	
	
	
	// Добавляет надпись
	// Два разных определения функции:
	// text( String $text, String $font, Int $size, String $color, Int $opacity, String $position, Int $margin = 0 )
	// text( String $text, String $font, Int $size, String $color, Int $opacity, Int $x_offset, Int $y_offset = 0 )
	public function text( $text, $font, $size, $color, $opacity = 100, $param1 = 0, $param2 = 0 ){	
		
		if ( !file_exists($font) ) 
			throw new ImageException( "Font not found in '$font'" );
		
		$size = (int) $size;
		$user_color = $this->color( $color, $opacity );
		$black = $this->color( '000', 50 );
		
		//вычисляем координаты
		$coords = imagettfbbox( $size, 0, $font, $text );
		$text_w = abs( $coords[0] ) + abs( $coords[2] );
		$text_h = abs( $coords[3] ) + abs( $coords[5] );
		list( $x_off, $y_off ) = $this->checkPositioning( $text_w, $text_h, $param1, $param2 );
		$y_off += $text_h;
		
		//рисуем тень
		ImageTTFText( $this->img, $size, 0, $x_off-1,	$y_off,		$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off+1,	$y_off,		$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off,		$y_off-1,	$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off,		$y_off+1,	$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off+1,	$y_off+1,	$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off+2,	$y_off+2,	$black, $font, $text );
		ImageTTFText( $this->img, $size, 0, $x_off,		$y_off,		$user_color, $font, $text );
	}
	
	
	
	// Сохраняет изображение в файл
	public function save( $destination = false, $type = 'jpeg', $quality = 85 ){
		
		if ( ($destination === 'output') or empty($destination) ){
			// header( 'Content-type: image/png' );
			// imagepng( $this->img, NULL, 9 );
			header( 'Content-type: image/jpeg' );
			imagejpeg( $this->img, NULL, 45 );
			return true;
		}
		
		switch (strtolower($type)){
			
			case 'jpg': 
			case 'jpeg':
				$status = imagejpeg( $this->img, $destination, $quality );
				break;
				
			case 'png':
				$q = round( (100 - $quality) * 9 / 100 );
				$status = imagepng( $this->img, $destination, $q );
				break;

			default:
				throw new ImageException( "Unknown output file type '$type'. Must be jpg, jpeg or png" );
		}
		
		if ( !$status )
			throw new ImageException( "Error when saving image. Image not saved" );
	}
	
	
	
	// Парсит цвет
	public function color( $color, $alpha = 100 ){
		if ( is_string($color) ){
			if ( $color[0] == '#' )
				$color = substr($color, 1);
			if ( strlen($color) == 6 )
				list( $r, $g, $b ) = array( 
						$color[0].$color[1], 
						$color[2].$color[3], 
						$color[4].$color[5] 
				);
			elseif ( strlen($color) == 3 )
				list( $r, $g, $b ) = array( 
						$color[0].$color[0], 
						$color[1].$color[1], 
						$color[2].$color[2] 
				);
			else{
				$r = 0;
				$g = 0;
				$b = 0;
			}
			$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
			
			if ( $alpha == 100 ){
				return imagecolorallocate( $this->img, $r, $g, $b );
			}
			else{
				$a = round( 127 - ($alpha / 100 * 127) );
				return imagecolorallocatealpha( $this->img, $r, $g, $b, $a );
			}
		}
		else
			return false;
	}
	
	
	
	
	// -------------------
	//  CLASS REALIZATION
	// -------------------
	
	// @param $position - something like 'top left center', 'bottom center' et cetera
	// @return - array( $xoffset, $yoffset )
	protected function getSemanticPositioning( $position, $s_w, $s_h, $margin = 0 ){
		$vert =	array( 
			'top'	=> 0, 
			'middle'=> 0,
			'bottom'=> 0
		);
		$vcount = 0;
		$horiz = array( 
			'left'	=> 0,
			'center'=> 0, 
			'right'	=> 0
		);
		$hcount = 0;
		
		// считаем количество слов для 
		// горизонтального и вертикального позиционирования
		$words = str_word_count( strtolower($position), 1 );
		foreach ( $words as $word ){
			if ( array_key_exists( $word, $vert) ){
				$vert[$word] ++;
				$vcount ++;
			}
			if ( array_key_exists( $word, $horiz) ){
				$horiz[$word] ++;
				$hcount ++;
			}
		}
		
		// Рассчитываем относительные смещения
		$h = ($horiz['left'] + 0.5*$horiz['center']) / ($hcount ? $hcount : 1);
		$v = ($vert['top']   + 0.5*$vert['middle'] ) / ($vcount ? $vcount : 1);
	
		//рассчитываем точные смещения
		$res_x = ($this->getX() - $s_w) * (1 - $h);
		if ( $h > 0.5 )
			$res_x = ($res_x > $margin) ? $res_x : $margin;
		if ( $h < 0.5 )
			$res_x = (($this->getX() - $res_x - $s_w) > $margin) ? 
					$res_x : ($this->getX() - $margin - $s_w);
		
		$res_y = ($this->getY() - $s_h) * (1 - $v);
		if ( $v > 0.5 )
			$res_y = ($res_y > $margin) ? $res_y : $margin;
		if ( $v < 0.5 )
			$res_y = (($this->getY() - $res_y - $s_h) > $margin) ? 
					$res_y : ($this->getY() - $margin - $s_h);
		
		//позиционтрование по-умолчанию - в центре
		if ( $hcount == 0 )	
			$res_x = round(($this->getX() - $s_w) / 2);
		if ( $vcount == 0 )	
			$res_x = round(($this->getY() - $s_h) / 2);
		
		return array( $res_x, $res_y );
	}
	
	//проверяет смещение
	protected function checkPositioning( $width, $height, $param1 = false, $param2 = false ){
		if ( is_string($param1) ){
			//если передан строковый параметр, используем относительное позиционирование
			$margin = (int) $param2;
			list( $x_off, $y_off ) = 
					$this->getSemanticPositioning( $param1, $width, $height, $margin );
		}
		elseif ( ($param1 === false) and ($param2 === false) ){
			//если ничего не задано, то позиционируем по центру
			$x_off = round( ($this->getX() - $width)  / 2 );
			$y_off = round( ($this->getY() - $height) / 2 );
		}
		else {
			//если переданы точные параметры, то используем абсолютное позиционирование
			$x_off = (int) $param1;
			$y_off = (int) $param2;
		}
		
		return array( $x_off, $y_off );
	}
	// -------------------
	//  CLASS REALIZATION
	// -------------------
	
	
	
	// GETTERS ------------------------------------------------
	
	// returns inner image resource of class
	public function getImageResource(){
		return $this->img;
	}
	
	// Возвращает ширину
	public function getX(){
		return imagesx( $this->img );
	}
	
	// Возвращает высоту
	public function getY(){
		return imagesy( $this->img );
	}
	
	// GETTERS ------------------------------------------------
	
	
	
	
	// SETTERS ------------------------------------------------
	
	// Устанавливает угол наклона текста
	public function setAngle( $angle ){
		$this->settings['angle'] = $angle;
	}
	
	// Устанавливает размер текста
	public function setFontSize( $font_size ){
		$this->settings['font-size'] = $font_size;
	}
	
	// Устанавливает путь до шрифта
	public function setFont( $font_face ){
		$this->settings['font-face'] = $font_face;
	}
	
	// SETTERS ------------------------------------------------
}
?>