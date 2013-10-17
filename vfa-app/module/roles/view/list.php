<div class="well well-small well-white">
	<?php if($this->tRoles):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('roles::update','roles::delete','roles::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Nom</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tRoles as $oRole):?>
			<tr>
				<?php if($oRole->role_name != 'owner'):?>
					<?php if(_root::getACL()->permit(array('roles::update','roles::delete','roles::read'))):?>
					<td class="col-md-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('roles::update')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oRole->role_name ?>" 
								href="<?php echo $this->getLink('roles::update',array('id'=>$oRole->getId()))?>">
								<i class="glyphicon glyphicon-edit"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('roles::delete')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oRole->role_name ?>" 
								href="<?php echo $this->getLink('roles::delete',array('id'=>$oRole->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('roles::read')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oRole->role_name ?>" 
								href="<?php echo $this->getLink('roles::read',array('id'=>$oRole->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					</td>
					<?php endif;?>	
					<td>
						<?php if(_root::getACL()->permit('roles::read')):?>
							<a href="<?php echo $this->getLink('roles::read',array('id'=>$oRole->getId()))?>"><?php echo $oRole->role_name ?></a>
						<?php else :?>
							<?php echo $oRole->role_name ?>
						<?php endif;?>
					</td>
				<?php else :?>
					<?php if(_root::getACL()->permit(array('roles::update','roles::delete','roles::read'))):?>
					<td></td>
					<?php endif;?>
					<td><?php echo $oRole->role_name ?></td>
				<?php endif;?>
				<td><?php echo $oRole->description ?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>

