<div id="modalForgottenPassword" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="" method="POST">
				<?php if ($this->__isset('tHidden')): ?>
					<?php foreach ($this->tHidden as $hidName => $hidValue): ?>
						<input type="hidden" name="<?php echo $hidName ?>" value="<?php echo $hidValue ?>"/>
					<?php endforeach; ?>
				<?php endif; ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>Mot de passe oublié ...</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'myEmail') ?>">
								<label for="inputMyEmail">Adresse Email</label>
								<input type="text" id="inputMyEmail" name="myEmail" class="form-control"
											 placeholder="Votre adresse Email"
											 value="<?php echo $this->oConnection->myEmail ?>" required autofocus autocomplete="off"/>
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'myEmail') ?></span>
							</div>
							<p>Saisissez l'adresse email associée à votre compte.</p>

							<p>Un message contenant un lien pour changer votre mot de passe va vous être envoyé.</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info" name="action" value="submitForgottenPassword">
						<i class="glyphicon glyphicon-ok with-text"></i>Ok
					</button>
					<button class="btn btn-default" data-dismiss="modal">
						<i class="glyphicon glyphicon-remove with-text"></i>Annuler
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if ($this->oConnection->openModalMessage): ?>
	<div id="modalMessage" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h4><?php echo $this->oConnection->textModalMessage ?></h4>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">
						<i class="glyphicon glyphicon-remove with-text"></i>Fermer
					</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
