<div class="well well-sm">
	<h1 class="text-center"><?php echo _root::getConfigVar('vfa-app.title') ?></h1>

	<h3 class="text-center">Site de vote du Prix de la Bande Dessinée</h3>
</div>
<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
	<div class="alert alert-warning clearfix">
		<p>
			<?php echo plugin_validation::show($this->tMessage, 'token') ?>
			<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('autoreg::index',
				array('id' => $this->oConfirm->invitation_id, 'key' => $this->oConfirm->invitation_key)) ?>">Fermer</a>
		</p>
	</div>
<?php else: ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->oConfirm->titleInvit ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<?php foreach ($this->oConfirm->tInscription as $label => $value): ?>
					<div class="col-sm-1 col-md-2 col-lg-1 view-label"><?php echo $label ?></div>
					<div class="col-sm-5 col-md-4 col-lg-5 view-value"><?php echo $value ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Validation de votre inscription</h3>
		</div>
		<div class="panel-body panel-condensed">
			<?php
			$btnAccount = '';
			$collapseInAccount = '';
			$btnLogin = '';
			$collapseInLogin = '';
			$btnPassword = '';
			$collapseInPassword = '';
			// Gère l'ouverture ou la fermeture du panel d'ouverture du compte
			if ($this->oConfirm->openAccount) {
				$collapseInAccount = ' in';
				$btnAccount = 'style="display:none;"';
			}
			// Gère l'ouverture ou la fermeture du panel d'identification
			if ($this->oConfirm->openLogin) {
				$collapseInLogin = ' in';
				$btnLogin = 'style="display:none;"';
			}
			// Gère l'ouverture ou la fermeture du panel Mot de passe
			if ($this->oConfirm->openPassword) {
				$collapseInPassword = ' in';
				$btnPassword = 'style="display:none;"';
			}
			?>
			<div class="panel-group" id="accordion">
				<div id="panelAccount" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-star-empty"></i></h1>
							</div>
							<div class="col-sm-7 col-lg-6">
								<h3>Vous n'avez pas de compte sur ce site ...</h3>
							</div>
							<div class="col-sm-4 col-lg-5">
								<a id="btnAccount" data-toggle="collapse" data-parent="#accordion" href="#collapseAccount"
									class="btn btn-default btn-lg btn-block margin-top-sm" <?php echo $btnAccount ?>>Enregistrez-vous !</a>
							</div>
						</div>
					</div>
					<div id="collapseAccount" class="panel-info collapse<?php echo $collapseInAccount ?>">
						<div class="panel-body panel-inner">
							<div class="row">
								<div class="col-sm-11 col-sm-offset-1">
									<?php echo $this->oViewFormAccount->show(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="panelLogin" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-star"></i></h1>
							</div>
							<div class="col-sm-7 col-lg-6">
								<h3>Vous avez déjà un compte sur ce site ...</h3>
							</div>
							<div class="col-sm-4 col-lg-5">
								<a id="btnLogin" data-toggle="collapse" data-parent="#accordion" href="#collapseLogin"
									class="btn btn-default btn-lg btn-block margin-top-sm" <?php echo $btnLogin ?>>
									Identifiez-vous !
								</a>
							</div>
						</div>
					</div>
					<div id="collapseLogin" class="panel-info collapse<?php echo $collapseInLogin ?>">
						<div class="panel-body panel-inner">
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<?php echo $this->oViewFormIdent->show(); ?>
									<a name="bottomLogin" href="#bottomLogin" id="bottomLogin"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="panelPassword" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-fire"></i></h1>
							</div>
							<div class="col-sm-7 col-lg-6">
								<h3>Mais vous avez oubliez votre mot de passe ...</h3>
							</div>
							<div class="col-sm-4 col-lg-5">
								<a id="btnPassword" data-toggle="collapse" data-parent="#accordion" href="#collapsePassword"
									class="btn btn-default btn-lg btn-block margin-top-sm" <?php echo $btnPassword ?>>
									Un peu d'aide ?</a>
							</div>
						</div>
					</div>
					<div id="collapsePassword" class="panel-info collapse<?php echo $collapseInPassword ?>">
						<div class="panel-body panel-inner">
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<?php echo $this->oViewForgottenPassword->show(); ?>
									<a name="bottomPassword" href="#bottomPassword" id="bottomPassword"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
