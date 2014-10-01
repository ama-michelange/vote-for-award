<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<small>Lecteurs du groupe</small>
			<?php echo $this->oGroup->toString() ?>
			<?php
			if ($this->tAwards) {
				echo '<small>inscrits à</small> ';
				$i = 0;
				foreach ($this->tAwards as $oAward) {
					if ($i > 0) :   echo ', '; endif;
//						echo '&laquo; '.$oAward->toString().' &raquo;';
					echo $oAward->toString();
					$i++;
				}
			}
			?>
		</h3>
	</div>
	<?php if (null != $this->oFirstAward && count($this->tUsers) > 0) : ?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Identifiant</th>
					<th>Email</th>
					<th>Nom</th>
					<th>Prénom</th>
					<?php if ($this->__isset('showGroup')): ?>
						<th>Groupe</th>
					<?php endif; ?>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<tr>
						<?php if (_root::getACL()->permit('users::read')): ?>
							<td>
								<a href="<?php echo $this->getLink('users::read', array('id' => $oUser->getId())) ?>">
									<?php echo wordwrap($oUser->login, 20, '<br />', true) ?></a>
							</td>
						<?php else: ?>
							<td><?php echo wordwrap($oUser->login, 20, '<br />', true) ?></td>
						<?php endif; ?>
						<td><?php echo wordwrap($oUser->email, 50, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->last_name, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->first_name, 30, '<br />', true) ?></td>
						<?php if ($this->__isset('showGroup')): ?>
							<td><?php echo $oUser->findGroupByRoleName(plugin_vfa::ROLE_READER)->toString(); ?></td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<?php if (!$this->oFirstAward): ?>
				<h4>Aucun prix en cours !</h4>
			<?php else: ?>
				<h4>Aucun lecteur inscrit !</h4>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
