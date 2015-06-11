<form id="toForgottenPassword" action="" method="POST">
	<?php if ($this->__isset('tHidden')): ?>
		<?php foreach ($this->tHidden as $hidName => $hidValue): ?>
			<input type="hidden" name="<?php echo $hidName ?>" value="<?php echo $hidValue ?>"/>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="panel panel-default panel-inner">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="glyphicon glyphicon-fire with-text"></i>J'ai oublié mon mot de passe !</h3>
		</div>
		<div class="panel-body">
			<p>Saisissez l'adresse email associée à votre compte.</p>

			<p>Vous recevrez un message pour saisir un nouveau mot de passe.</p>

			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'myEmail') ?>">
				<label for="inputMyEmail">Adresse Email</label>
				<input type="text" id="inputMyEmail" name="myEmail" class="form-control" placeholder="Votre adresse Email"
							 value="<?php echo $this->oConnection->myEmail ?>" autocomplete="off"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'myEmail') ?></span>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button type="submit" class="btn btn-info" name="action" value="submitForgottenPassword">
					<i class="glyphicon glyphicon-ok with-text"></i>Ok
				</button>
				<a id="cancelPassword" class="btn btn-default" href="<?php echo $this->getLink('default::index') ?>">
					<i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			</div>
		</div>
	</div>
</form>
