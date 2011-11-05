<!DOCTYPE html>
<html>
<head>
	<title><?= $title ? $title : 'Ice Storm' ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" /> 
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
	<link rel="stylesheet" href="/themes/default/style.css?<?=rand(0,999)?>" />
<?= $styles ?>
	
	<!-- Libraries -->
	<script type="text/javascript" src="/js/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="/js/jquery.form.js"></script>
<?= $scripts ?>

	<!-- Application -->
	<!-- <script type="text/javascript" src="js/app.js"></script> -->
</head>
<body>
<div id="header">
	<span class="logo"><a href="<?= WEBURL ?>">Ice Storm</a></span>
<?= menu() ?>
<?= auth_form() ?>
</div>
<div id="body">
