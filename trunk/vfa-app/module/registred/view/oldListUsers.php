<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oGroup->toString() ?></h3>
	</div>
	<?php if ($this->tUsers): ?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<?php if ($this->invite && _root::getACL()->permit(array('invitations::reader', 'invitations::board'))): ?>
						<th></th>
					<?php endif; ?>
					<th>Identifiant</th>
					<th>Email</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th class="col-xs-1" style="text-align:center;" data-rel="tooltip" data-original-title="Correspondant de son groupe">
						Corres.
					</th>
					<th>Inscrit</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<?php
					switch (_root::getAction()) {
						case 'listReadGroup':
							$typeAward = plugin_vfa::TYPE_AWARD_READER;
							break;
						case 'listBoardGroup':
							$typeAward = plugin_vfa::TYPE_AWARD_BOARD;
							break;
						default:
							$typeAward = null;
							break;
					}
					$tAwards = model_award::getInstance()->findAllValidByUserId($oUser->user_id, $typeAward);
					?>
					<tr>
						<?php if ($this->invite /*&& _root::getACL()->permit(array('invitations::reader','invitations::board'))*/): ?>
							<td>
								<?php if (0 == count($tAwards)): ?>
									<div class="btn-group">
										<?php if (_root::getACL()->permit('invitations::reader')): ?>
											<a rel="tooltip" data-original-title="Inviter <?php echo $oUser->toString() ?>"
												href="<?php echo $this->getLink('invitations::reader', array('idUser' => $oUser->getId())) ?>">
												<i class="glyphicon glyphicon-envelope"></i>
											</a>
										<?php endif; ?>
										<?php if (_root::getACL()->permit('invitations::board')): ?>
											<a rel="tooltip" data-original-title="Inviter <?php echo $oUser->toString() ?>"
												href="<?php echo $this->getLink('invitations::board', array('idUser' => $oUser->getId())) ?>">
												<i class="glyphicon glyphicon-envelope"></i>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<?php if (_root::getACL()->permit('registred::read')): ?>
							<td><a href="<?php echo $this->getLink('registred::read',
									array('id' => $oUser->getId())) ?>"><?php echo wordwrap($oUser->login, 20, '<br />', true) ?></a></td>
						<?php else: ?>
							<td><?php echo wordwrap($oUser->login, 20, '<br />', true) ?></td>
						<?php endif; ?>
						<td><?php echo wordwrap($oUser->email, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->last_name, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->first_name, 30, '<br />', true) ?></td>
						<td style="text-align:center;">
							<?php if ($oUser->isInRole(plugin_vfa::TYPE_RESPONSIBLE)) : ?>
								<span class="glyphicon glyphicon-check"></span>
							<?php endif; ?>
						</td>
						<td>
							<?php
							$i = 0;
							foreach ($tAwards as $oAward) {
								if ($i > 0) :   echo ', '; endif;
								echo $oAward->toString();
								$i++;
							}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
