<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Titre
<!--			--><?php //echo $this->title ?>
		</h3>
	</div>

	<?php if ($this->tRegins): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-condensed">
				<thead>
				<tr>
					<th>Destinataire</th>
					<th>Etat</th>
					<th>Type</th>
					<th>Prix</th>
					<th>Groupe</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tRegins as $oRegin): ?>
					<?php
					switch ($oRegin->type) {
						case plugin_vfa::TYPE_BOARD:
							$typeColor = 'label label-danger';
							break;
						case plugin_vfa::TYPE_RESPONSIBLE:
							$typeColor = 'label label-warning';
							break;
						case plugin_vfa::TYPE_READER:
							$typeColor = '';
							break;
						default:
							$typeColor = '';
							break;
					}
					switch ($oRegin->state) {
						case plugin_vfa::STATE_OPEN:
							$stateColor = 'label-warning';
							$stateTip = 'L\'invitation est ouverte mais l\'email n\'a pas été envoyé à ' . $oRegin->email;
							break;
						case plugin_vfa::STATE_SENT:
							$stateColor = 'label-info';
							$stateTip = 'L\'invitation a été envoyé par email';
							break;
						case plugin_vfa::STATE_ACCEPTED:
							$stateColor = 'label-success';
							$stateTip = $oRegin->email . ' a accepté l\'invitation.';
							break;
						case plugin_vfa::STATE_REJECTED:
							$stateColor = 'label-danger';
							$stateTip = 'Le destinataire, ' . $oRegin->email . ',  a refusé l\'invitation.';
							break;
						default:
							$stateColor = '';
							$stateTip = '';
							break;
					}
					?>
					<tr>
						<td>
							<?php if (_root::getACL()->permit('invitations::read')): ?>
								<a href="<?php echo $this->getLink('invitations::read', array('id' => $oRegin->getId())) ?>">
									<?php echo wordwrap($oRegin->email, 36, ' ', true) ?>
								</a>
							<?php else: ?>
								<?php echo wordwrap($oRegin->email, 36, ' ', true) ?>
							<?php endif; ?>
						</td>
						<td>
						<span class="label <?php echo $stateColor ?>" data-rel="tooltip"
									data-original-title="<?php echo $stateTip ?>"><?php echo $oRegin->showState() ?></span>
							<?php if (_root::getACL()->permit('invitations::send') &&
								($oRegin->state == plugin_vfa::STATE_OPEN || $oRegin->state == plugin_vfa::STATE_REJECTED)
							) : ?>
								&nbsp;
								<a rel="tooltip"
									 data-original-title="Renvoyer l'invitation par email à <?php echo $oRegin->email ?>"
									 href="<?php echo $this->getLink('invitations::send', array('id' => $oRegin->getId())) ?>">
									<i class="glyphicon glyphicon-envelope"></i>
								</a>
							<?php endif; ?>
						</td>
						<td><span class="<?php echo $typeColor ?>"><?php echo $oRegin->showType() ?></span></td>
						<td>
							<?php
							$tAwards = $oRegin->findAwards();
							$i = 0;
							foreach ($tAwards as $award) {
								if ($i > 0) {
									echo '<br>';
								};
								echo $award->toString();
								$i++;
							}
							?>
						</td>
						<td>
							<?php
							$oGroup = $oRegin->findGroup();
							echo $oGroup->group_name;
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<h4>Aucune Regin !</h4>
		</div>
	<?php endif; ?>
</div>
