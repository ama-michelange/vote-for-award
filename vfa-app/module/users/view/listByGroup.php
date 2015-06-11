<div class="panel panel-default panel-root">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des utilisateurs par groupe</h3> -->
	<!-- 	</div> -->
	<?php if ($this->tGroups): ?>
		<div class="panel-body">
			<form class="form-horizontal" method="POST">
				<div class="row">
					<div class="col-sm-1">
						<label for="inputGroups">Groupe</label>
					</div>
					<div class="col-xs-5">
						<select id="inputGroups" class="form-control" name="selectedGroup" size="13">
							<?php foreach ($this->tGroups as $oGroup): ?>
								<option value="<?php echo $oGroup->group_id ?>"
									<?php if ($this->SelectedIdGroup == $oGroup->group_id): echo 'selected'; endif; ?>>
									<?php echo $oGroup->group_name ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-1">
						<button type="submit" class="btn btn-sm btn-default">
							<i class="glyphicon glyphicon-refresh"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
	<?php endif; ?>
	<?php if ($this->tUsers): ?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<?php if (_root::getACL()->permit(array('users::update', 'users::delete', 'users::read'))): ?>
						<th></th>
					<?php endif; ?>
					<th>Identifiant</th>
					<th>Email</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Groupes</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<tr>
						<?php if (_root::getACL()->permit(array('users::update', 'users::delete', 'users::read'))): ?>
							<td class="col-xs-1">
								<div class="btn-group">
									<?php if (_root::getACL()->permit('users::update')): ?>
										<a rel="tooltip" data-original-title="Modifier <?php echo $oUser->login ?>"
											 href="<?php echo $this->getLink('users::update', array('id' => $oUser->getId())) ?>"> <i
												class="glyphicon glyphicon-edit"></i></a>
									<?php endif; ?>
									<?php if (_root::getACL()->permit('users::delete')): ?>
										<a rel="tooltip" data-original-title="Supprimer <?php echo $oUser->login ?>"
											 href="<?php echo $this->getLink('users::delete', array('id' => $oUser->getId())) ?>"> <i
												class="glyphicon glyphicon-trash"></i></a>
									<?php endif; ?>
									<?php if (_root::getACL()->permit('users::read')): ?>
										<a rel="tooltip" data-original-title="Voir <?php echo $oUser->login ?>"
											 href="<?php echo $this->getLink('users::read', array('id' => $oUser->getId())) ?>"> <i
												class="glyphicon glyphicon-eye-open"></i></a>
									<?php endif; ?>
								</div>
							</td>
						<?php endif; ?>
						<?php if (_root::getACL()->permit('users::read')): ?>
							<td><a
									href="<?php echo $this->getLink('users::read', array('id' => $oUser->getId())) ?>"><?php echo $oUser->login ?></a>
							</td>
						<?php else: ?>
							<td><?php echo $oUser->login ?></td>
						<?php endif; ?>
						<td><?php echo $oUser->email ?></td>
						<td><?php echo $oUser->last_name ?></td>
						<td><?php echo $oUser->first_name ?></td>
						<td>
							<?php
							$i = 0;
							foreach ($oUser->findGroups() as $oGroup) :
								if ($i > 0) :
									echo ', ';
								endif;
								echo $oGroup->group_name;
								$i++;
							endforeach;
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>

