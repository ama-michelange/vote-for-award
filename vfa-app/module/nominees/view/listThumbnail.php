<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			Nominés de la sélection <?php echo $this->oSelection->toString() ?>
		</h3>
	</div>
	<div class="panel-body">
		<?php if($this->toTitles):?>
			<?php foreach($this->toTitles as $oTitle):?>
				<div class="panel-images">
					<div class="panel-images-body">
						<?php foreach($oTitle->findDocs() as $oDoc):?>
							<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-sm',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idSelection'=>$this->oSelection->selection_id))); ?>
						<?php endforeach;?>
						<div class="panel-images-title">
							<p>
								<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(),new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idSelection'=>$this->oSelection->selection_id))); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach;?>
		<?php else: ?>
			<h4>Aucun nominé !</h4>
		<?php endif;?>
	</div>
</div>
