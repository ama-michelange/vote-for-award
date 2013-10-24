<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Liste des albums</h3>
	</div>
	<?php if($this->tGroups):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('groups::update','groups::delete','groups::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Nom</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tGroups as $oGroup):?>
			<tr>
				<?php if(_root::getACL()->permit(array('groups::update','groups::delete','groups::read'))):?>
					<td class="col-md-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('groups::update')):?>
							<a rel="tooltip" data-original-title="Modifier <?php echo $oGroup->group_name ?>" 
								href="<?php echo $this->getLink('groups::update',array('id'=>$oGroup->getId()))?>">
								<i class="glyphicon glyphicon-edit"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('groups::delete')):?>
							<a rel="tooltip" data-original-title="Supprimer <?php echo $oGroup->group_name ?>" 
								href="<?php echo $this->getLink('groups::delete',array('id'=>$oGroup->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('groups::read')):?>
							<a rel="tooltip" data-original-title="Voir <?php echo $oGroup->group_name ?>" 
								href="<?php echo $this->getLink('groups::read',array('id'=>$oGroup->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					</td>
				<?php endif;?>
				<?php if(_root::getACL()->permit('groups::read')):?>
					<td><a href="<?php echo $this->getLink('groups::read',array('id'=>$oGroup->getId()))?>"><?php echo $oGroup->group_name ?></a></td>
				<?php else:?>
					<td><?php echo $oGroup->group_name ?></td>
				<?php endif;?>
				<td<?php if('BOARD'==$oGroup->type) { echo ' class="text-warning"'; }?>><?php echo $oGroup->getTypeString() ?></td>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
