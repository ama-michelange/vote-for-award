<!--<div class="well well-sm">-->
<!--	<h1 class="text-center margin-bottom-max">Bureau de votes-->
<!--		<small class="text-nowrap">du Prix de la BD INTER CE</small>-->
<!--	</h1>-->
<!--</div>-->
<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
	<div class="alert alert-warning clearfix">
		<p>
			<?php echo plugin_validation::show($this->tMessage, 'token') ?>
			<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('default::code') ?>">Fermer</a>
		</p>
	</div>
<?php else: ?>
	<?php echo $this->oViewRegistryDetail->show(); ?>
	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Identification</h3>
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
			if ($this->oRegistry->openAccount) {
				$collapseInAccount = ' in';
				$btnAccount = 'style="display:none;"';
			} // Gère l'ouverture ou la fermeture du panel d'identification
			elseif ($this->oRegistry->openLogin) {
				$collapseInLogin = ' in';
				$btnLogin = 'style="display:none;"';
			} // Gère l'ouverture ou la fermeture du panel Mot de passe
			elseif ($this->oRegistry->openPassword) {
				$collapseInPassword = ' in';
				$btnPassword = 'style="display:none;"';
			}
			?>
			<div class="panel-group" id="accordion">
				<div id="panelAccount" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-xs-2 col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-star-empty"></i></h1>
							</div>
							<div class="col-xs-10 col-sm-7 col-lg-7">
								<h3>Vous n'avez pas de compte sur <?php echo _root::getConfigVar('vfa-app.title') ?></h3>
							</div>
							<div class="col-xs-10 col-sm-4 col-lg-4 pull-right">
								<a id="btnAccount" data-toggle="collapse" data-parent="#accordion" href="#collapseAccount"
									 class="btn btn-default btn-lg btn-block margin-top-sm" <?php echo $btnAccount ?>>Enregistrez-vous
									!</a>
							</div>
						</div>
					</div>
					<div id="collapseAccount" class="panel-info collapse<?php echo $collapseInAccount ?>">
						<div class="panel-body panel-inner">
							<div class="row">
								<div class="col-sm-11 col-sm-offset-1">
									<a name="bottomAccount" href="#bottomAccount" id="bottomAccount"></a>
									<?php echo $this->oViewFormAccount->show(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="panelLogin" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-xs-2 col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-star"></i></h1>
							</div>
							<div class="col-xs-10 col-sm-7 col-lg-7">
								<h3>Vous avez déjà un compte sur <?php echo _root::getConfigVar('vfa-app.title') ?></h3>
							</div>
							<div class="col-xs-10 col-sm-4 col-lg-4 pull-right">
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
									<a name="bottomLogin" href="#bottomLogin" id="bottomLogin"></a>
									<?php echo $this->oViewFormIdent->show(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="panelPassword" class="panel panel-info">
					<div class="panel-body panel-inner">
						<div class="row">
							<div class="col-xs-2 col-sm-1 col-lg-1">
								<h1 class="text-center text-warning margin-top-lg"><i class="glyphicon glyphicon-fire"></i></h1>
							</div>
							<div class="col-xs-10 col-sm-7 col-lg-7">
								<h3>Mais vous avez oublié votre mot de passe</h3>
							</div>
							<div class="col-xs-10 col-sm-4 col-lg-4 pull-right">
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
									<a name="bottomPassword" href="#bottomPassword" id="bottomPassword"></a>
									<?php echo $this->oViewForgottenPassword->show(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->oViewModalMessage->show(); ?>
	<?php if ($this->oViewModalEnd): ?>
		<?php echo $this->oViewModalEnd->show(); ?>
	<?php endif; ?>
<?php endif; ?>
