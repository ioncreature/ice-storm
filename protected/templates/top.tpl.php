<!DOCTYPE html>
<html>
<head>
	<title><?= Template::title() ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="<?= WEBURL ?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?= WEBURL ?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?= WEBURL ?>themes/default/style.css?<?= rand(0,9999) ?>" />
<?= Template::block( 'styles' ) ?>

	<!-- JavaScript config -->
<?= Template::show( 'js.config' ) ?>

	<!-- Libraries -->
	<script type="text/javascript" src="<?= WEBURL ?>js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="<?= WEBURL ?>js/jquery.form.js"></script>
<?= Template::block( 'scripts' ) ?>

	<!-- Module scripts -->
	<script type="text/javascript">
<?= Template::block( 'js' ) ?>
	</script>

	<!-- Additional head entries -->
<?= Template::block( 'head' ) ?>
</head>

<body class="claro">

<div id="global_content_wrapper">
	<div id="header">
		<div class="h_wrapper">
			<span class="logo"><a class="transition_all_03" href="<?= WEBURL ?>">Ice Storm</a></span>
			<!-- TOP MENU -->
			<?= Template::show( 'include/menu' ) ?>
			<div class="left_section transition_all_03">
				<div class="ls_wrapper">
					<!--AUTH FORM -->
					<?= Template::show( 'include/auth_form' ) ?>
				</div>
			</div>
		</div>
	</div>
	<div class="body_wrapper">
		<div id="body">
<!-- BODY CONTENT START -->
<?= Template::block( 'body' ) ?>