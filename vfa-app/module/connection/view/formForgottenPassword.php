<form id="toForgottenPassword" action="" method="POST">
	<?php if ($this->__isset('tHidden')): ?>
		<?php foreach ($this->tHidden as $hidName => $hidValue): ?>
			<input type="hidden" name="<?php echo $hidName ?>" value="<?php echo $hidValue ?>"/>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="panel panel-default panel-inner">
		<div class="panel-heading">
			<h3 class="panel-title">Mot de passe oublié ...</h3>
		</div>
		<div class="panel-body">
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'myEmail') ?>">
				<label for="inputMyEmail">Adresse Email</label>
				<input type="text" id="inputMyEmail" name="myEmail" class="form-control" placeholder="Votre adresse Email"
						 value="<?php echo $this->oConnection->myEmail ?>" autocomplete="off"/>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'myEmail') ?></span>
			</div>
			<p>Saisissez l'adresse email associée à votre compte.</p>
			<p>Un message contenant un lien pour changer votre mot de passe va vous être envoyé.</p>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button type="submit" class="btn btn-info" name="action" value="submitForgottenPassword">
					<i class="glyphicon glyphicon-ok with-text"></i>Ok
				</button>
				<a id="cancelPassword" class="btn btn-default"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			</div>
		</div>
	</div>
</form>
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