<?php
/**
 * Config for client-side scripts
 * @author Marenin Alex
 * December 2011
 */
?>

<script type="text/javascript">
	var app = app || {};

	app.config = {
		url: '<?= WEBURL ?>',

		service: {
			department: '<?= WEBURL . 'service/department/'?>',
			staff: '<?= WEBURL . 'service/staff/'?>'
		}
	};
</script>
