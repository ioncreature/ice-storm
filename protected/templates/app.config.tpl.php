<?php
/**
 * Config for client-side scripts
 * @author Marenin Alex
 * December 2011
 */
?>

<script type="text/javascript">
	if ( window.dojo )
		dojo.provide( 'app.config' );
	else
		app = app || {};
	app.config = {

		url: '<?= WEBURL ?>',

		service: {
			department: '<?= WEBURL . 'service/department/'?>'
		}
	};
</script>
