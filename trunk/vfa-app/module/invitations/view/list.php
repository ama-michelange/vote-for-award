<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->title ?></h3>
	</div>

	<?php if($this->tInvitations):?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-condensed">
		<thead>
				<tr>
					<?php if(_root::getACL()->permit(array('invitations::update','invitations::delete','invitations::read'))):?>
						<th></th>
					<?php endif;?>
					<th>Destinataire</th>
					<th>Etat</th>
					<th>Type</th>
					<th>Prix</th>
					<th>Groupe</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tInvitations as $oInvitation):?>
				<?php
				switch ($oInvitation->type) {
					case plugin_vfa::TYPE_BOARD:
						$typeColor = 'label-danger';
						break;
					case plugin_vfa::TYPE_RESPONSIBLE:
						$typeColor = 'label-warning';
						break;
					case plugin_vfa::TYPE_READER:
						$typeColor = '';
						break;
					default:
						$typeColor = '';
						break;
				}
				switch ($oInvitation->state) {
					case plugin_vfa::STATE_OPEN:
						$stateColor = 'label-warning';
						$stateTip = 'L\'invitation est ouverte mais l\'email n\'a pas été envoyé à ' . $oInvitation->email;
						break;
					case plugin_vfa::STATE_SENT:
						$stateColor = 'label-info';
						$stateTip = 'L\'invitation a été envoyé par email';
						break;
					case plugin_vfa::STATE_ACCEPTED:
						$stateColor = 'label-success';
						$stateTip = $oInvitation->email . ' a accepté l\'invitation.';
						break;
					case plugin_vfa::STATE_REJECTED:
						$stateColor = 'label-danger';
						$stateTip = 'Le destinataire, ' . $oInvitation->email . ',  a refusé l\'invitation.';
						break;
					default:
						$stateColor = '';
						$stateTip = '';
						break;
				}
					?>
				<tr>
					<?php if(_root::getACL()->permit(array('invitations::update','invitations::delete','invitations::read'))):?>
						<td class="col-xs-1">
							<div class="btn-group">
								<?php if(_root::getACL()->permit('invitations::send')):?>
									<a rel="tooltip"
										data-original-title="Envoyer l'invitation par email à <?php echo $oInvitation->email ?>"
										href="<?php echo $this->getLink('invitations::send',array('id'=>$oInvitation->getId()))?>">
										<i class="glyphicon glyphicon-envelope"></i>
									</a>
								<?php endif;?>
								<?php if(_root::getACL()->permit('invitations::update')):?>
									<a rel="tooltip"
										data-original-title="Accéder à l'invitation pour <?php echo $oInvitation->email ?>" target="_new"
										href="<?php echo plugin_vfa::generateURLInvitation($oInvitation) ?>"> <i
										class="glyphicon glyphicon-share-alt"></i>
									</a>
								<?php endif;?>
								<?php if(_root::getACL()->permit('invitations::delete')):?>
									<a rel="tooltip"
										data-original-title="Supprimer l'invitation pour <?php echo $oInvitation->email ?>"
										href="<?php echo $this->getLink('invitations::delete',array('id'=>$oInvitation->getId()))?>">
										<i class="glyphicon glyphicon-trash"></i>
									</a>
								<?php endif;?>
								<?php if(_root::getACL()->permit('invitations::read')):?>
									<a rel="tooltip"
										data-original-title="Voir l'invitation pour <?php echo $oInvitation->email ?>"
										href="<?php echo $this->getLink('invitations::read',array('id'=>$oInvitation->getId()))?>">
										<i class="glyphicon glyphicon-eye-open"></i>
									</a>
								<?php endif;?>
							</div>
						</td>
					<?php endif;?>
					<td><?php echo wordwrap( $oInvitation->email,30,' ', true) ?></td>
					<td>
						<span class="label <?php echo $stateColor ?>" data-rel="tooltip"
								data-original-title="<?php echo $stateTip ?>"><?php echo $oInvitation->showState() ?></span>
					</td>
					<td><span class="label <?php echo $typeColor ?>"><?php echo $oInvitation->showType() ?></span></td>
					<td>
					<?php
						$tAwards = $oInvitation->findAwards();
						$i = 0;
						foreach ($tAwards as $award) {
							if ($i > 0) {
								echo '<br>';
							}
							;
							echo $award->toString();
							$i ++;
						}
					?>
					</td>
					<td>
					<?php
						$oGroup = $oInvitation->findGroup();
						echo $oGroup->group_name;
					?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php else: ?>
		<div class="panel-body">
			<h4>Aucune invitation !</h4>
		</div>
	<?php endif;?>
</div>
