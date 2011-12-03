<!DOCTYPE html>
<html>
<head>
	<title><?= Template::title() ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="/themes/default/style.css?<?= rand(0,9999) ?>" />
<?= Template::block( 'styles' ) ?>

	<!-- Libraries -->
	<script type="text/javascript" src="/js/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="/js/jquery.form.js"></script>
<?= Template::block( 'scripts' ) ?>


	<!-- Application config -->
<?= Template::show( 'app.config.tpl.php' ) ?>

	<!-- Module scripts -->
	<script type="text/javascript">
		<?= Template::block( 'js' ) ?>
	</script>

	<?= Template::block( 'head' ) ?>
</head>
<body>
<div id="global_content_wrapper">
<div id="header">
	<div class="h_wrapper">
		<span class="logo"><a class="transition_all_03" href="<?= WEBURL ?>">Ice Storm</a></span>
		<?= menu() ?>
		<div class="left_section transition_all_03">
			<div class="ls_wrapper">
			<?= auth_form() ?>
			</div>
		</div>
	</div>
</div>
<div class="body_wrapper">
<div id="body" class="<?= Template::block( 'body_class' ) ?>">
<?= Template::block( 'body' ) ?>
