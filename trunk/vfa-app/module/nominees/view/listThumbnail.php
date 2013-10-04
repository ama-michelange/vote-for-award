<div class="well well-small well-white">
	<h3>
		<?php if(_root::getACL()->permit('awards::read')):?>
			<a href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->award_id))?>">
				<?php echo $this->oAward->getTypeNameString() ?></a>
		<?php else:?>
			<?php echo $this->oAward->getTypeNameString() ?>
		<?php endif;?>
			: les sélectionnés
	</h3>
	<?php if($this->toTitles):?>
	<ul class="thumbnails bd-thumbnail">
		<?php foreach($this->toTitles as $oTitle):?>
		<li>
			<div class="img-thumbnail clearfix">
				<p><strong>
					<?php if(_root::getACL()->permit('nominees::read')):?>
					<a	href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
						<?php echo $oTitle->toString() ?>
					</a>
					<?php else:?>
						<?php echo $oTitle->toString() ?>
					<?php endif;?>			
				</strong></p>
				<div class="bd-thumb">
					<ul class="thumbnails">
					<?php foreach($oTitle->findDocs() as $oDoc):?>
						<li>
							<img src="<?php echo $oDoc->image ?>" alt="">
						</li>
					<?php endforeach;?>
					</ul>
				</div>		 
				<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>     		
					<div class="btn-group">
						<?php if(_root::getACL()->permit('nominees::update')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oTitle->toString() ?>" 
							href="<?php echo $this->getLink('nominees::update',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-pencil"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('nominees::delete')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oTitle->toString() ?>" 
							href="<?php echo $this->getLink('nominees::delete',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-trash"></i></a>
						<?php endif;?>
						<?php if(_root::getACL()->permit('nominees::read')):?>
						<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oTitle->toString() ?>" 
							href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
							<i class="glyphicon glyphicon-eye-open"></i></a>
						<?php endif;?>
					</div>
				<?php endif;?>
			</div>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
</div>
