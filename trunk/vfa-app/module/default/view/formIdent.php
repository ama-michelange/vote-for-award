<form id="toIdentify" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="regin_id" value="<?php echo $this->oRegistry->regin_id ?>"/>
	<input type="text" name="hiddenLogin" value="" style="display: none"/>
	<input type="password" name="hiddenPassword" value="" style="display: none"/>

	<div class="panel panel-default panel-inner">
		<div class="panel-heading">
			<h3 class="panel-title">
				Identifiez-vous
				<small>pour confirmer l'inscription</small>
			</h3>
		</div>
		<div class="panel-body">
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_login') ?>">
				<label for="cfLogin">Identifiant</label>
				<input class="form-control" type="text" id="cfLogin" name="cf_login" placeholder="Votre identifiant de connexion"
						 value="<?php echo $this->oRegistry->cf_login ?>"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_login') ?></span>
			</div>
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_password') ?>">
				<label for="cfPassword">Mot de passe</label>
				<input class="form-control" type="password" id="cfPassword" name="cf_password" placeholder="Votre mot de passe"
						 value="<?php echo $this->oRegistry->cf_password ?>"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_password') ?></span>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button type="submit" class="btn btn-info" name="action" value="toIdentify">
					<i class="glyphicon glyphicon-ok with-text"></i>S'identifier
				</button>
				<a id="cancelLogin" class="btn btn-default"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			</div>
		</div>
	</div>
</form>
