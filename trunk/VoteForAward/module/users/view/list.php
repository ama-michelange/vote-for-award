<div class="well well-small well-white">
	<?php if($this->tUsers):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('users::update','users::delete','users::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Pseudo</th>
				<th>Email</th>
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
				<td class="col-md-1">
					<div class="btn-group">
						<?php if(_root::getACL()->permit('users::update')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oUser->username ?>" 
							href="<?php echo $this->getLink('users::update',array('id'=>$oUser->getId()))?>">
							<i class="glyphicon glyphicon-pencil"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('users::delete')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oUser->username ?>" 
							href="<?php echo $this->getLink('users::delete',array('id'=>$oUser->getId()))?>">
							<i class="glyphicon glyphicon-trash"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('users::read')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oUser->username ?>" 
							href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>">
							<i class="glyphicon glyphicon-eye-open"></i></a>
						<?php endif;?>
					</div>
				</td>
				<?php endif;?>
				<?php if(_root::getACL()->permit('users::read')):?>
					<td><a href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"><?php echo $oUser->username ?></a></td>
				<?php else:?>
					<td><?php echo $oUser->username ?></td>
				<?php endif;?>
				<td><?php echo $oUser->email ?></td>
				<td><?php echo $oUser->last_name ?></td>
				<td><?php echo $oUser->first_name ?></td>
				<td>
					<?php 
						$i=0;  
						foreach($oUser->findGroups() as $oGroup):
						if($i > 0): echo ', '; endif;
						echo $oGroup->group_name;
						$i++; 
						endforeach;
					?>
				</td>
				<?php if(_root::getACL()->permit('roles')):?>
				<td>
					<?php 
						$i=0;  
						foreach($oUser->findRoles() as $oRole):
						if($i > 0): echo ', '; endif;
						echo $oRole->role_name;
						$i++; 
						endforeach;
					?>
				</td>
				<?php endif;?>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
