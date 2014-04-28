<div class="panel panel-default">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des utilisateurs</h3> -->
	<!-- 	</div> -->
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
					<th>Groupes</th>
					<?php if(_root::getACL()->permit('roles')):?>
						<th>Rôles</th>
					<?php endif;?>
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
							foreach ($oUser->findGroups() as $oGroup) {
								if ($i > 0) :	echo ', ';	endif;
								echo $oGroup->group_name;
								$i++;
							}
						?>
					</td>
					<?php if(_root::getACL()->permit('roles')):?>
					<td>
						<?php
							$i = 0;
							foreach ($oUser->findRoles() as $oRole) {
								if ($i > 0) :	echo ', '; endif;
								echo $oRole->role_name;
								$i ++;
							}
						?>
					</td>
					<?php endif;?>
				</tr>	
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
