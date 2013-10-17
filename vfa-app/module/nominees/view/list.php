<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?>
			<small><i class="glyphicon glyphicon-chevron-right"></i></small>
			Les titres sélectionnés
		</h3>	
	</div>
	<?php if($this->toTitles):?>
	<table class="table table-striped">
		<tbody>
			<?php foreach($this->toTitles as $oTitle):?>
			<tr>
				<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>
				<td class="col-xs-2 col-sm-2 col-md-2 col-lg-1">
					<div class="btn-group">
						<?php if(_root::getACL()->permit('nominees::update')):?>
						<a rel="tooltip" data-original-title="Modifier <?php echo $oTitle->toString() ?>" data-container="body"
							href="<?php echo $this->getLink('nominees::update',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-edit"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('nominees::delete')):?>
						<a rel="tooltip" data-original-title="Supprimer <?php echo $oTitle->toString() ?>" data-container="body"
							href="<?php echo $this->getLink('nominees::delete',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-trash"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('nominees::read')):?>
						<a rel="tooltip" data-original-title="Voir <?php echo $oTitle->toString() ?>" data-container="body"
							href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-eye-open"></i></a>
						<?php endif;?>
					</div>
				</td>
				<?php endif;?>
				<td>
					<?php if(_root::getACL()->permit('nominees::read')):?>
					<a	href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
						<?php echo $oTitle->toString() ?>
					</a>
					<?php else:?>
						<?php echo $oTitle->toString() ?>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
