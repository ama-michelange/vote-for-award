<?php if ($this->oRegin->openModalConfirm): ?>
	<div id="modalConfirmValidate" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="" method="POST">
					<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
					<input type="hidden" name="regin_id" value="<?php echo $this->oRegin->getId() ?>"/>
					<?php foreach ($this->tReginUsers as $oReginUsers): ?>
						<?php
						$name = 'unknown[]';
						switch ($oReginUsers->accepted) {
							case 1:
								$name = 'accepted[]';
								break;
							case -1:
								$name = 'rejected[]';
								break;
						}
						?>
						<input type="hidden" name="<?php echo $name ?>" value="<?php echo $oReginUsers->getId() ?>"/>
					<?php endforeach; ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">Confirmation</h4>
					</div>
					<div class="modal-body">
						<?php if ($this->oRegin->nbAccepted > 0) : ?>
							<h4>Inscriptions acceptées : <?php echo $this->oRegin->nbAccepted ?>
								/ <?php echo count($this->tReginUsers) ?></h4>
						<?php endif; ?>
						<?php if ($this->oRegin->nbRejected > 0) : ?>
							<h4>Inscriptions refusées : <?php echo $this->oRegin->nbRejected ?>
								/ <?php echo count($this->tReginUsers) ?></h4>
						<?php endif; ?>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-info" name="action" value="toConfirm">
							<i class="glyphicon glyphicon-ok with-text"></i>Confirmer
						</button>
						<button class="btn btn-default" data-dismiss="modal">
							<i class="glyphicon glyphicon-remove with-text"></i>Annuler
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>
