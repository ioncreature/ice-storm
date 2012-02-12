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
		var dojoConfig = {
			async: true,
			baseUrl: "<?= WEBURL ?>js/",
			tlmSiblingOfDojo: false,
			packages: [
				{ name: "dojo", location: "dojo" },
				{ name: "dijit", location: "dijit" },
				{ name: "dojox", location: "dojox" },
				{ name: "app", location: "app", main: "app" }
			],
			parseOnLoad: true,
			has: {
				"dojo-firebug": true,
				"dojo-debug-messages": true
			},
			gfxRenderer: "svg,silverlight,vml",
			locale: 'ru'
		};

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
