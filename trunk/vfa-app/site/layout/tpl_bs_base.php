<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo _root::getConfigVar('vfa-app.title') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link href="site/assets/css/bootstrap-slate-3.3.0.css" rel="stylesheet" media="screen">
	<style>
		body {
			/*padding-top: 83px;*/
			/* 60px to make the container go all the way to the bottom of the topbar */
		}
	</style>
	<link href="site/select2/select2.css" rel="stylesheet">
	<link href="site/select2/select2-bootstrap.css" rel="stylesheet">
	<link href="site/css/bs-apps-slate-3.3.0.css" rel="stylesheet">


	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Fav and touch icons -->
	<link rel="shortcut icon" href="site/ico/favicon.ico">


</head>

<body>
<div class="container">
	<?php echo $this->load('work') ?>
</div>

<!-- 	<script src="http://code.jquery.com/jquery-latest.js"></script> -->
<script src="site/assets/js/jquery-1.11.0.min.js"></script>
<script src="site/assets/js/bootstrap.min-3.3.0.js"></script>
<script src="site/assets/js/bootstrap-datepicker.js"></script>
<script src="site/select2/select2.min.js"></script>
<script src="site/js/bs-apps.js"></script>
<?php echo $this->load('script') ?>
</body>


</html>