<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			Nominés de la sélection <?php echo $this->oSelection->toString() ?>
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
			<h4>Aucun nominé !</h4>
		<?php endif;?>
	</div>
</div>
