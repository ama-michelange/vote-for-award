<div class="well well-sm">
	<h1 class="text-center margin-bottom-max">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
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
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Changement de mot de passe</h3>
		</div>
		<div class="panel-body panel-condensed">
			<form id="toRegistry" action="" method="POST">
				<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
				<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
				<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>"/>
				<input type="hidden" name="email" value="<?php echo $this->oConfirm->email ?>"/>
				<input type="text" name="hiddenLogin" value="" style="display: none"/>
				<input type="password" name="hiddenPassword" value="" style="display: none"/>

				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h3 class="panel-title">Saisissez un nouveau</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newPassword') ?>">
									<label for="newPassword">Mot de passe
										<span class="btn btn-xs btn-link" data-rel="tooltip"
												data-original-title="Le mot de passe doit contenir entre 7 et 30 caractères">
											<i class="glyphicon glyphicon-info-sign"></i>
										</span>
									</label>
									<input class="form-control" type="password" id="newPassword" name="newPassword"
											 value="<?php echo $this->oConfirm->newPassword ?>" placeholder="Votre mot de passe"/>
									<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newPassword') ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div
									class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmPassword') ?>">
									<label for="confirmPassword">Confirmation du mot de passe</label>
									<input class="form-control" type="password" id="confirmPassword" name="confirmPassword"
											 value="<?php echo $this->oConfirm->confirmPassword ?>" placeholder="Confirmez votre mot de passe"/>
									<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmPassword') ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<div class="pull-right">
							<button type="submit" class="btn btn-info" name="action" value="toRegistry">
								<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
							</button>
<!--							<a id="cancelAccount" class="btn btn-default"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>-->
						</div>
					</div>
				</div>
				<a name="newValidation"></a>
			</form>
		</div>
	</div>
<?php endif; ?>
