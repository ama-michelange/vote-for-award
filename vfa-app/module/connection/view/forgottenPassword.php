<div class="well well-sm">
	<h1 class="text-center margin-bottom-max">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
</div>
<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
	<div class="alert alert-warning clearfix">
		<p>
			<?php echo plugin_validation::show($this->tMessage, 'token') ?>
			<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('default::index') ?>">Fermer</a>
		</p>
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6">
			<?php echo $this->oViewForgottenPassword->show(); ?>
		</div>
	</div>
	<?php echo $this->oViewModalMessage->show(); ?>
<?php endif; ?>
