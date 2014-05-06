<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oGroup->group_name ?></h3>
	</div>
	<?php if($this->tUsers):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Login</th>
					<th>Email</th>
					<th>Alias</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Rôles</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tUsers as $oUser):?>
				<tr>
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
						<?php
							$i = 0;
							foreach ($oUser->findRoles() as $oRole) {
								if ($i > 0) :	echo ', '; endif;
								echo model_role::getInstance()->getI18nStringRole($oRole->role_id);
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
