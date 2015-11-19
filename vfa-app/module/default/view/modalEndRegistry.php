<div id="modalEnd" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="" method="POST">
				<input type="hidden" name="regin_id" value="<?php echo $this->oRegistry->regin_id ?>"/>
				<input type="hidden" name="user_id" value="<?php echo $this->oRegistry->oUser->user_id ?>"/>
				<div class="modal-body">
					<?php if ($this->oRegistry->createAccount) : ?>
						<h4>Le compte est créé</h4>
						<dl class="dl-horizontal">
							<dt>Utilisateur</dt>
							<dd><?php echo $this->oRegistry->oUser->toStringFirstLastName() ?></dd>
							<dt>Identifiant</dt>
							<dd><?php echo $this->oRegistry->oUser->login ?></dd>
						</dl>
					<?php else: ?>
						<h4>Compte identifié</h4>
						<dl class="dl-horizontal">
							<dt>Utilisateur</dt>
							<dd><?php echo $this->oRegistry->oUser->toStringFirstLastName() ?></dd>
						</dl>
					<?php endif; ?>
					<h4>Affectation</h4>
					<dl class="dl-horizontal">
						<dt>Groupe</dt>
						<dd><?php echo $this->oRegistry->oRegin->findGroup()->toString() ?></dd>
						<?php if ($this->oRegistry->oUser->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) : ?>
							<dt>Correspondant</dt>
							<dd><span class="glyphicon glyphicon-check"></span></dd>
						<?php endif; ?>
					</dl>
					<?php if (plugin_vfa::PROCESS_INTIME == $this->oRegistry->oRegin->process)  : ?>
						<h4>Inscription</h4>
					<?php else: ?>
						<?php if (plugin_vfa::TYPE_BOARD == $this->oRegistry->oRegin->type)  : ?>
							<h4 class="text-warning">A valider par l'organisateur</h4>
						<?php else: ?>
							<h4 class="text-warning">A valider par le correspondant</h4>
						<?php endif ?>
					<?php endif; ?>
					<dl class="dl-horizontal">
						<dt>Participation</dt>
						<dd><?php echo $this->oRegistry->oRegin->toStringAllAwards() ?></dd>
					</dl>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info" name="action" value="toConnect">
						<i class="glyphicon glyphicon-log-in with-text"></i>Connexion
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
