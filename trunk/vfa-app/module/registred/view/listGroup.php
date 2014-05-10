<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oGroup->group_name ?></h3>
	</div>
	<?php if($this->tUsers):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if($this->invite && _root::getACL()->permit(array('invitations::reader','invitations::board'))):?>
						<th></th>
					<?php endif;?>
					<th>Login</th>
					<th>Email</th>
					<th>Alias</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th data-rel="tooltip" data-original-title="Correspondant du groupe">Corres.</th>
					<th>Inscrit</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tUsers as $oUser):?>
					<?php
						$tAwards = model_award::getInstance()->findAllValidByUserId($oUser->user_id);
					?>
					<tr>
						<?php if($this->invite && _root::getACL()->permit(array('invitations::reader'))):?>
							<td class="col-xs-1">
								<?php if (0==count($tAwards)): ?>
									<div class="btn-group">
										<?php if(_root::getACL()->permit('invitations::reader')):?>
											<a rel="tooltip"
												data-original-title="Inviter <?php echo $oUser->toString() ?>"
												href="<?php echo $this->getLink('invitations::reader',array('idUser'=>$oUser->getId()))?>">
												<i class="glyphicon glyphicon-envelope"></i>
											</a>
										<?php endif;?>
									</div>
								<?php endif;?>
							</td>
						<?php endif;?>
						<?php if(_root::getACL()->permit('users::read')):?>
							<td><a href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"><?php echo wordwrap($oUser->login,20,'<br />', true) ?></a></td>
						<?php else:?>
							<td><?php echo wordwrap($oUser->login,20,'<br />', true) ?></td>
						<?php endif;?>
						<td><?php echo wordwrap($oUser->email,30,'<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->alias,30,'<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->last_name,30,'<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->first_name,30,'<br />', true) ?></td>
						<td>
							<?php foreach ($oUser->findRoles() as $oRole) : ?>
								<?php if (plugin_vfa::TYPE_RESPONSIBLE == $oRole->role_name) :	?>
									<span class="glyphicon glyphicon-check"></span>
								<?php endif; ?>
							<?php endforeach;?>
						</td>
						<td>
							<?php
								$i = 0;
								foreach ($tAwards as $oAward) {
									if ($i > 0) :	echo ', '; endif;
									echo $oAward->toString();
									$i ++;
								}
							?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
