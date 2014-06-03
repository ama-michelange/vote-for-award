<form id="toIdentify" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
	<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>"/>
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
			<div
				class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_login') ?>">
				<label for="cfLogin">Identifiant</label>
				<input class="form-control" type="text" id="cfLogin" name="cf_login" placeholder="Votre identifiant de connexion"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_login') ?></span>
			</div>
			<div
				class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_password') ?>">
				<label for="cfPassword">Mot de passe</label>
				<input class="form-control" type="password" id="cfPassword" name="cf_password" placeholder="Votre mot de passe"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_password') ?></span>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<a id="cancelLogin" class="btn btn-default"><i class="glyphicon glyphicon-ok with-text"></i>Annuler</a>
				<button type="submit" class="btn btn-default" name="action" value="toIdentify">
					<i class="glyphicon glyphicon-ok with-text"></i>S'identifier
				</button>
			</div>
			<a href="#">Mot de passe oublié ?</a>
		</div>
	</div>
</form>
