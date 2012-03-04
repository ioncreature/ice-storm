<?php
/**
 * @author Marenin Alex
 *         February 2012
 */

Template::add_css( WEBURL .'js/dijit/themes/claro/claro.css' );
Template::add_css( WEBURL .'js/dijit/themes/dijit.css' );
Template::add_js ( WEBURL .'js/dojo/dojo.js' );
Template::add_js ( WEBURL .'js/app/page/Students.js' );
?>

<?php if ( $data['action'] === 'edit' ): ?>
	<h2>Редактирование данных студента</h2>
	<h2><?= $data['student']->Human->full_name ?></h2>
<?php else: ?>
	<h2>Добавление студента</h2>
<?php endif ?>

<?= Template::show( 'form/student', $data ) ?>
