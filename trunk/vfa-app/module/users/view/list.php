<div class="panel panel-default">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des utilisateurs</h3> -->
	<!-- 	</div> -->
	<?php if($this->tUsers):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if(_root::getACL()->permit(array('users::update','users::delete','users::read'))):?>
						<th></th>
					<?php endif;?>
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
					<?php if(_root::getACL()->permit(array('users::update','users::delete','users::read'))):?>
					<td class="col-xs-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('users::update')):?>
							<a rel="tooltip" data-original-title="Modifier <?php echo $oUser->login ?>"
								href="<?php echo $this->getLink('users::update',array('id'=>$oUser->getId()))?>"> <i
								class="glyphicon glyphicon-edit"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('users::delete')):?>
							<a rel="tooltip" data-original-title="Supprimer <?php echo $oUser->login ?>"
								href="<?php echo $this->getLink('users::delete',array('id'=>$oUser->getId()))?>"> <i
								class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('users::read')):?>
							<a rel="tooltip" data-original-title="Voir <?php echo $oUser->login ?>"
								href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"> <i
								class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
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
						<?php
			$i = 0;
			foreach ($oUser->findGroups() as $oGroup) :
				if ($i > 0) :
					echo ', ';
				 endif;
				echo $oGroup->group_name;
				$i ++;
			endforeach
			;
			?>
					</td>
					<?php if(_root::getACL()->permit('roles')):?>
					<td>
						<?php
				$i = 0;
				foreach ($oUser->findRoles() as $oRole) :
					if ($i > 0) :
						echo ', ';
					 endif;
					echo $oRole->role_name;
					$i ++;
				endforeach
				;
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
