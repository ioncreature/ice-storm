<?php
/**
 * Config for client-side scripts
 * @author Marenin Alex
 * December 2011
 */
?>
	<script type="text/javascript">
		/**
		 * Dojo config
		 */
		var djConfig = {
			parseOnLoad: true,
			isDebug: true,
			gfxRenderer: "svg,silverlight,vml"
		};
		var dojoConfig = djConfig;

		/**
		 * Application config
		 */
		var app = app || {};
		app.config = {
			url: '<?= WEBURL ?>',

			service: {
				department: '<?= WEBURL . 'service/department/' ?>',
				staff: '<?= WEBURL . 'service/staff/' ?>'
			},

			page: {
				employee: '<?= WEBURL . 'org/employee/' ?>'
			}
		};
	</script>
