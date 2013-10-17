<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?>
			<small><i class="glyphicon glyphicon-chevron-right"></i></small>
			Les titres sélectionnés
		</h3>	
	</div>
	<div class="panel-body">
	<?php if($this->toTitles):?>
		<?php foreach($this->toTitles as $oTitle):?>
			<div class="panel-images panel-images-dark">
				<div class="panel-images-body">
					<?php foreach($oTitle->findDocs() as $oDoc):?>
						<img class="img-md" src="<?php echo $oDoc->image ?>" alt="">
					<?php endforeach;?>
				</div>
				<div class="caption">
				<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>     		
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
				<?php endif;?>
				</div>
			</div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
