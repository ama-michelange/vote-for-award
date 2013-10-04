<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Site</title>
<link rel="stylesheet" type="text/css" href="site/css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="site/css/base.css" media="screen" />
<script src="site/js/main.js" type="text/javascript"></script>
</head>
<body>
	<div id="main">
		<div id="header">
			<?php echo $this->load('authent') ?>
		</div>
		<div id="content">
			<div id="menu">
				<?php echo $this->load('menu') ?>
			</div>
			<div id="work">
				<?php echo $this->load('work') ?>
			</div>
		</div>
		<div id="footer">
			<?php echo $this->load('footer') ?>
		</div>
	</div>
</body>
</html>
