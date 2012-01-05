<?php
// Функция для вывода постраничной навигации
function paginator( array $params ){
	$defaults = array(
		'page_current' => 1,
		'items_per_page' => 10,
		'items_total' => 1,
		'url_pattern' => '::page::',
		'page_show' => 5 // сколько показывать страниц после и перед текущей
	);
	$p = array_merge( $defaults, $params );
	
	$first_page_link = false;
	$last_page_link = false;
	$pages = ceil($p['items_total'] / $p['items_per_page']);
	$page_start = 1;
	$page_end = $pages;
	
	if ( $p['page_current'] - $p['page_show'] > 1 ){
		$first_page_link = true;
		$page_start = $p['page_current'] - $p['page_show'];
	}
	if ( $pages - $p['page_current'] > $p['page_show'] ){
		$last_page_link = true;
		$page_end = $p['page_current'] + $p['page_show'];
	}
	?>
	
	<?php if ( $pages > 1 ): ?>
		<div class="paginator">
		
		<?php if ( $first_page_link ): ?>
			<a href="<?= str_replace( '::page::', '1', $p['url_pattern'] ) ?>">&lt;&lt;</a>
		<?php endif; ?>
		
		<?php for ( $i = $page_start; $i <= $page_end; $i++ ): ?>
			<?php if ( $i === $p['page_current'] ): ?>
				<span class="current"><?= $i ?></span>
			<?php else: ?>
				<a href="<?= str_replace( '::page::', (string) $i, $p['url_pattern'] ) ?>"><?=$i?></a>
			<?php endif; ?>
		<?php endfor; ?>
			
		<?php if ( $last_page_link ): ?>
			<a href="<?= str_replace( '::page::', (string) $pages, $p['url_pattern'] ) ?>">&gt;&gt;</a>
		<?php endif; ?>
		
		</div>
	<?php endif;
}
?>