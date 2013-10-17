<div class="well well-small well-white">
	<?php if($this->tTitles):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('titles::update','titles::delete','titles::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Titre</th>
				<th>Tomes inclus</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tTitles as $oTitle):?>
			<tr>
				<?php if(_root::getACL()->permit(array('titles::update','titles::delete','titles::read'))):?>
					<td class="col-md-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('titles::update')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oTitle->title ?>" 
								href="<?php echo $this->getLink('titles::update',array('id'=>$oTitle->getId()))?>">
								<i class="glyphicon glyphicon-edit"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('titles::delete')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oTitle->title ?>" 
								href="<?php echo $this->getLink('titles::delete',array('id'=>$oTitle->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('titles::read')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oTitle->title ?>" 
								href="<?php echo $this->getLink('titles::read',array('id'=>$oTitle->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					</td>
				<?php endif;?>
				<?php if(_root::getACL()->permit('titles::read')):?>
					<td><a href="<?php echo $this->getLink('titles::read',array('id'=>$oTitle->getId()))?>"><?php echo $oTitle->title ?></a></td>
				<?php else:?>
					<td><?php echo $oTitle->title ?></td>
				<?php endif;?>
				<td><?php echo $oTitle->numbers ?></td>
				</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
