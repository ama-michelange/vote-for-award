<form id="registry" action="<?php echo $this->getLink('default::registry') ?>" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>

	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">S'inscrire</h3>
		</div>
		<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
			<div class="panel-body">
				<div class="alert alert-warning clearfix">
					<p><?php echo plugin_validation::show($this->tMessage, 'token') ?>
						<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a>
					</p>
				</div>
			</div>
		<?php else: ?>
			<div class="panel-body panel-condensed">
				<div class="row">
					<div class="col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'code') ?>">
							<label for="inputCode">Code d'inscription</label>
							<input class="form-control" type="text" id="inputCode" name="code"
										 value="<?php echo $this->oRegistry->code ?>" autofocus="true"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'code') ?></span>
							Saisissez le code que l'on vous a transmis pour vous inscrire.
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<div class="pull-right">
					<button class="btn btn-info" type="submit" name="action" value="toGetCode">
						<i class="glyphicon glyphicon-ok with-text"></i>Continuer
					</button>
					<a class="btn btn-default" href="<?php echo $this->getLink('default::index') ?>">
						<i class="glyphicon glyphicon-remove with-text"></i>Annuler
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</form>
