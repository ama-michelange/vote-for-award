<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<small>Invitation de</small> <?php echo $this->oInvitation->email ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-body panel-condensed">
						<dl class="dl-horizontal">
							<dt>Invité</dt>
							<dd><?php echo $this->oInvitation->email ?></dd>
						</dl>
						<dl class="dl-horizontal">
							<dt>Créée le</dt>
							<dd>
								<?php echo plugin_vfa::toStringDateShow($this->oInvitation->created_date) ?>
								à
								<?php echo plugin_vfa::toStringTimeShow($this->oInvitation->created_date) ?>
							</dd>
							<dt>par</dt>
							<dd><?php echo $this->oCreatedUser->toString() ?></dd>
						</dl>
						<?php if ($this->oInvitation->modified_date): ?>
							<dl class="dl-horizontal">
								<dt>Envoyée le</dt>
								<dd>
									<?php echo plugin_vfa::toStringDateShow($this->oInvitation->modified_date) ?>
									à
									<?php echo plugin_vfa::toStringTimeShow($this->oInvitation->modified_date) ?>
								</dd>
							</dl>
						<?php endif; ?>
						<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_OWNER) || _root::getConfigVar('vfa-app.invitation.access.enabled')
						): ?>
							<dl class="dl-horizontal">
								<dt>
									<i class="glyphicon glyphicon-share-alt"></i>
								</dt>
								<dd>
									<a href="<?php echo plugin_vfa::generateURLInvitation($this->oInvitation) ?>" target="_new">Voir
										l'invitation</a>
								</dd>
							</dl>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-body panel-condensed">
						<dl class="dl-horizontal">
							<dt>Inscription</dt>
							<dd><?php echo $this->oInvitation->showState() ?></dd>
							<dt>pour</dt>
							<?php foreach ($this->tAwards as $oAward): ?>
								<dd><?php echo $oAward->toString() ?></dd>
							<?php endforeach; ?>
						</dl>
						<dl class="dl-horizontal">
							<dt>en tant que</dt>
							<dd><?php echo $this->oInvitation->showType() ?></dd>
							<dt>du groupe</dt>
							<dd><?php echo $this->oGroup->group_name ?></dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
