<div class="well well-sm">
	<h1 class="text-center"><?php echo _root::getConfigVar('vfa-app.title') ?></h1>
</div>
<div class="panel panel-warning">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oConfirm->titleInvit ?></h3>
	</div>
	<div class="panel-body">
		<h3><?php echo $this->oConfirm->textInvit ?></h3>

		<div class="pull-right"><a class="btn btn-default" href="<?php echo $this->getLink('default::index') ?>">Accès au
				site</a></div>
	</div>
</div>
