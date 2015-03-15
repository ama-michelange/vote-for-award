<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->title ?></h3>
	</div>
	<?php if ($this->tUsers): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th class="col-xs-1" style="text-align:center;" data-rel="tooltip" data-original-title="Correspondant de son groupe">
						Corres.
					</th>
					<?php if ($this->__isset('showGroup')): ?>
						<th>Groupe</th>
					<?php endif; ?>
					<?php if ($this->__isset('showPersonal')): ?>
						<th>Email</th>
						<th>Identifiant</th>
					<?php endif; ?>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<tr>
						<?php if (_root::getACL()->permit('users::read')): ?>
							<td>
								<a href="<?php echo $this->getLink('users::read', array('id' => $oUser->getId())) ?>">
									<?php echo $oUser->last_name ?></a>
							</td>
						<?php else: ?>
							<td><?php echo $oUser->last_name ?></td>
						<?php endif; ?>
						<td><?php echo $oUser->first_name ?></td>
						<td style="text-align:center;">
							<?php if ($oUser->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) : ?>
								<span class="glyphicon glyphicon-check"></span>
							<?php endif; ?>
						</td>
						<?php if ($this->__isset('showGroup')): ?>
							<td><?php echo $oUser->findGroupByRoleName(plugin_vfa::ROLE_READER)->toString(); ?></td>
						<?php endif; ?>
						<?php if ($this->__isset('showPersonal')): ?>
							<td><?php echo $oUser->email ?></td>
							<td><?php echo $oUser->login ?></td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
