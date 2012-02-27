<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_js(  WEBURL .'js/dojo/dojo.js');
Template::add_js ( WEBURL .'js/app/page/Students.js' );
?>

<?= Template::show( 'form/student', $data ) ?>
