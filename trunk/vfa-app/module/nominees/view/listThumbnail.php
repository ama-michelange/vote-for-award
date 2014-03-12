<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?> : la sélection
		</h3>
	</div>
	<?php if($this->toTitles):?>
	<div class="panel-body">
		<?php foreach($this->toTitles as $oTitle):?>
			<div class="panel-images">
				<div class="panel-images-body">
					<p>
						<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(),new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))); ?>
					</p>
					<?php foreach($oTitle->findDocs() as $oDoc):?>
						<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-sm',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))); ?>
					<?php endforeach;?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>
