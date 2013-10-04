<div class="panel panel-default">
	<?php if($this->tDocs):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('docs::update','docs::delete','docs::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Titre</th>
				<th>N°</th>
				<th>Titre propre</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tDocs as $oDoc):?>
			<tr>
				<?php if(_root::getACL()->permit(array('docs::update','docs::delete','docs::read'))):?>
				<td class="col-xs-2 col-sm-2 col-md-2 col-lg-1">
					<div class="btn-group">
						<?php if(_root::getACL()->permit('docs::update')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oDoc->toString() ?>" 
								href="<?php echo $this->getLink('docs::update',array('id'=>$oDoc->getId()))?>">
								<i class="glyphicon glyphicon-pencil"></i></a>
						<?php endif;?>						
						<?php if(_root::getACL()->permit('docs::delete')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oDoc->toString() ?>" 
								href="<?php echo $this->getLink('docs::delete',array('id'=>$oDoc->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
						<?php endif;?>						
						<?php if(_root::getACL()->permit('docs::read')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oDoc->toString() ?>" 
								href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
						<?php endif;?>						
					</div>
				</td>
				<?php endif;?>						
				<td>
					<?php if(_root::getACL()->permit('docs::read')):?>
						<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"><?php echo $oDoc->title ?></a>
					<?php else:?>
						<?php echo $oDoc->title ?>
					<?php endif;?>						
				</td>
				<td><?php echo $oDoc->number ?></td>
				<td><?php echo $oDoc->proper_title ?></td>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
