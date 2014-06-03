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
			<!--		<div class="row">-->
			<!--			<div class="col-sm-6 col-md-6">-->
			<!--				<div class="panel panel-default panel-inner">-->
			<!--					<div class="panel-body">-->
			<?php foreach ($this->oConfirm->tInscription as $label => $value): ?>
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-2 view-label"><?php echo $label ?></div>
					<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $value ?></div>
				</div>
			<?php endforeach; ?>
			<!--					</div>-->
			<!--				</div>-->
			<!--			</div>-->
			<!--		</div>-->
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Validation de votre inscription</h3>
		</div>
		<div class="panel-body panel-condensed">
			<?php
			$displayAccount = '';
			$displayLogin = '';
			// Gère l'ouverture ou la fermeture du panel d'ouverture du compte
			if ($this->oConfirm->openAccount) {
				$collapseInAccount = ' in';
				$displayLogin = 'style="display:none;"';
			} else {
				$collapseInAccount = '';
			}
			// Gère l'ouverture ou la fermeture du panel d'identification
			if ($this->oConfirm->openLogin) {
				$collapseInLogin = ' in';
				$displayAccount = 'style="display:none;"';
			} else {
				$collapseInLogin = '';
			}
			?>
			<div class="panel-group" id="accordion">
				<div id="panelAccount" class="panel panel-info" <?php echo $displayAccount ?>>
				<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-sm-1">
								<h1 class="text-center text-warning"><i class="glyphicon glyphicon-star-empty"></i></h1>
							</div>
							<div class="col-sm-11">
								<h3>
									Vous n'avez pas de compte sur ce site ...
									<a id="btnAccount" data-toggle="collapse" data-parent="#accordion" href="#collapseAccount"
										class="btn btn-default" <?php echo $displayLogin ?>><i class="glyphicon glyphicon-record with-text"></i>Enregistrez-vous
										!</a>
								</h3>
							</div>
						</div>
					</div>
					<div id="collapseAccount" class="panel-info collapse<?php echo $collapseInAccount ?>">
						<div class="panel-body panel-inner">
							<div class="row">
								<div class="col-sm-11 col-sm-offset-1">
									<?php echo $this->oViewFormAccount->show(); ?>
									<a name="bottomAccount" href="#bottomAccount" id="bottomAccount"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="panelLogin" class="panel panel-info" <?php echo $displayLogin ?>>
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-sm-1">
								<h1 class="text-center text-warning"><i class="glyphicon glyphicon-star"></i></h1>
							</div>
							<div class="col-sm-11">
								<h3>
									Vous avez déjà un compte sur ce site ...
									<a id="btnLogin" data-toggle="collapse" data-parent="#accordion" href="#collapseLogin"
										class="btn btn-default" <?php echo $displayAccount ?>><i
											class="glyphicon glyphicon-user with-text"></i>Identifiez-vous !</a>
								</h3>
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
			</div>
		</div>
	</div>
<?php endif; ?>
