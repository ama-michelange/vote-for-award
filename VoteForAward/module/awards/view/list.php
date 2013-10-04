<div class="well well-small well-white">
	<?php if($this->tAwards):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('awards::update','awards::delete','awards::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Nom</th>
				<th>Début</th>
				<th>Fin</th>
				<th>Affichage</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tAwards as $oAward):?>
			<tr>
				<?php if(_root::getACL()->permit(array('awards::update','awards::delete','awards::read'))):?>
					<td class="col-md-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('awards::update')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oAward->getTypeNameString() ?>" 
								href="<?php echo $this->getLink('awards::update',array('id'=>$oAward->getId()))?>">
								<i class="glyphicon glyphicon-pencil"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('awards::delete')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oAward->getTypeNameString() ?>" 
								href="<?php echo $this->getLink('awards::delete',array('id'=>$oAward->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('awards::read')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oAward->getTypeNameString() ?>" 
								href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					</td>
				<?php endif;?>
				<td>
					<?php if(_root::getACL()->permit('awards::read')):?>
						<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"><?php echo $oAward->getTypeNameString() ?></a>
					<?php else:?>
						<?php echo $oAward->getTypeNameString() ?>
					<?php endif;?>
				</td>
				<td><?php echo plugin_vfa::toStringDateShow($oAward->start_date) ?></td>
				<td><?php echo plugin_vfa::toStringDateShow($oAward->end_date) ?></td>
				<td><?php echo $oAward->getShowString() ?></td>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>

