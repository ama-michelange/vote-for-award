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
	<table class="table table-striped">
		<tbody>
			<?php foreach($this->toTitles as $oTitle):?>
			<tr>
				<td>
					<div>
		      		<p><strong>
							<?php if(_root::getACL()->permit('nominees::read')):?>
							<a	href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
								<?php echo $oTitle->toString() ?>
							</a>
							<?php else:?>
								<?php echo $oTitle->toString() ?>
							<?php endif;?>
		      		</strong></p>
					</div>
					<div>
						<div class="pull-left">
							<?php foreach($oTitle->findDocs() as $oDoc):?>
							<img src="<?php echo $oDoc->image ?>" alt="" style="height:70px; margin-left:10px;">
							<?php endforeach;?>
						</div>
						<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>
    					<div class="btn-group btn-group-vertical pull-left" style="margin-left:10px;">
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
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
