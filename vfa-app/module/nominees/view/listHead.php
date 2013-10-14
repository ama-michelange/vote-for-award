<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i>
			<?php if(_root::getACL()->permit('awards::read')):?>
				<a href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->award_id))?>">
					<?php echo $this->oAward->getTypeNameString() ?></a>
			<?php else:?>
				<?php echo $this->oAward->getTypeNameString() ?>
			<?php endif;?>
			: la liste des titres sélectionnés
		</h3>
	</div>
	<?php if($this->toTitles):?>
	<table class="table table-striped">
		<tbody>
			<?php foreach($this->toTitles as $oTitle):?>
			<tr>
				<td>
					<div>
						<?php foreach($oTitle->findDocs() as $oDoc):?>
							<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 pull-left">
								<img class="img-responsive" src="<?php echo $oDoc->image ?>" alt="">						
							</div>
						<?php endforeach;?>
						<div class="pull-left">
							<h4 style="margin-left:10px;">
								<?php if(_root::getACL()->permit('nominees::read')):?>
									<a	href="<?php echo $this->getLink('nominees::read',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
										<?php echo $oTitle->toString() ?>
									</a>
								<?php else:?>
									<?php echo $oTitle->toString() ?>
								<?php endif;?>
			      		</h4>
							<?php if(_root::getACL()->permit(array('nominees::update','nominees::delete','nominees::read'))):?>
		    					<div class="btn-group" style="margin-left:10px;">
									<?php if(_root::getACL()->permit('nominees::update')):?>
										<a rel="tooltip" data-original-title="Modifier <?php echo $oTitle->toString() ?>" data-container="body"
											href="<?php echo $this->getLink('nominees::update',array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))?>">
											<i class="glyphicon glyphicon-pencil"></i></a>
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
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
