<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="glyphicon glyphicon-th with-text"></i>Les titres sélectionnés :
			<?php if(_root::getACL()->permit('awards::read')):?>
				<a href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->award_id))?>">
					<?php echo $this->oAward->getTypeNameString() ?></a>
			<?php else:?>
				<?php echo $this->oAward->getTypeNameString() ?>
			<?php endif;?>
			
		</h3>
	</div>
	<?php if($this->toTitles):?>
	<div class="panel-body">
		<?php foreach($this->toTitles as $oTitle):?>
			<div class="panel-images panel-images-horizontal">
				<div class="panel-images-body">
					<?php foreach($oTitle->findDocs() as $oDoc):?>
						<img class="img-sm" src="<?php echo $oDoc->image ?>" alt="">
					<?php endforeach;?>
				</div>
				<div class="caption">
					<h4><?php echo $oTitle->toString() ?></h4>
					<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>     		
						<div class="btn-group">
							<?php if(_root::getACL()->permit('nominees::update')):?>
							<a rel="tooltip" data-original-title="Modifier <?php echo $oTitle->toString() ?>"
								href="<?php echo $this->getLink('nominees::update',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
								<i class="glyphicon glyphicon-pencil"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('nominees::delete')):?>
							<a rel="tooltip" data-original-title="Supprimer <?php echo $oTitle->toString() ?>"
								href="<?php echo $this->getLink('nominees::delete',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('nominees::read')):?>
							<a rel="tooltip" data-original-title="Voir <?php echo $oTitle->toString() ?>"
								href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					<?php endif;?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>
