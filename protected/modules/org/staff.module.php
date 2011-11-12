<?php
/**
 * Staff module
 * Marenin Alex
 * November 2011
 */

// JS + CSS
Template::add_js( 'js/dojo/dojo.js', array( 'djConfig' => 'parseOnLoad: true, isDebug: true' ) );
Template::add_js( '/js/app/init.js' );
Template::add_css( '/js/dijit/themes/claro/claro.css' );

//
// Вывод
//
Template::ob_to_block( 'body' ); ?>

<h1>Hello, i'm a staff page</h1>

<?php Template::ob_block_end(); ?>





