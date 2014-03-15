<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="text-muted">
				<?php echo plugin_BsHtml::showNavLabel('Sélection '.$this->oSelection->toString(),new NavLink('selections', 'read', array('id'=>$this->oSelection->selection_id))); ?>
			</span>
			<span class="text-muted">/</span>
			Albums sélectionnés
		</h3>
	</div>
	<div class="panel-body">
		<?php if($this->toTitles):?>
			<?php foreach($this->toTitles as $oTitle):?>
				<div class="panel-images panel-images-dark">
					<div class="panel-images-body">
						<?php foreach($oTitle->findDocs() as $oDoc):?>
							<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-md',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idSelection'=>$this->oSelection->selection_id)), true); ?>
						<?php endforeach;?>
					</div>
				</div>
			<?php endforeach;?>
		<?php else: ?>
			<h3>Aucun album sélectionné !</h3>
		<?php endif;?>
	</div>
</div>
