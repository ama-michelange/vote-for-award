<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?php echo _root::getConfigVar('vfa-app.title') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link href="site/assets/css/bootstrap-slate-3.3.2.css" rel="stylesheet" media="screen">
	<!-- <link href="site/assets/css/bootstrap-theme.css" rel="stylesheet" media="screen"> -->
	<style>
		body {
			padding-top: 107px;
			/* 60px to make the container go all the way to the bottom of the topbar */
		}
	</style>
	<link href="site/assets/css/datepicker.css" rel="stylesheet">
	<link href="site/select2/select2.css" rel="stylesheet">
	<link href="site/select2/select2-bootstrap.css" rel="stylesheet">
	<link href="site/css/bs-apps-slate-3.3.2.css" rel="stylesheet" media="screen">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Fav and touch icons -->
	<link rel="shortcut icon" href="site/ico/favicon.ico">


</head>

<body>

<?php echo $this->load('bsnav-top') ?>
<?php echo $this->load('bsnavbar') ?>
<div class="container">
	<?php echo $this->load('work') ?>
</div>


<!-- 	<script src="http://code.jquery.com/jquery-latest.js"></script> -->
<script src="site/assets/js/jquery-1.11.0.min.js"></script>
<script src="site/assets/js/bootstrap.min-3.3.2.js"></script>
<script src="site/assets/js/bootstrap-datepicker.js"></script>
<script src="site/select2/select2.min.js"></script>
<script src="site/js/bs-apps.js"></script>
<?php echo $this->load('script') ?>
</body>


</html>
