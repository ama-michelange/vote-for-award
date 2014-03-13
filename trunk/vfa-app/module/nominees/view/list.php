<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?> : la sélection
		</h3>
	</div>
	<?php if($this->toTitles):?>
	<table class="table table-striped table-image">
		<tbody>
			<?php foreach($this->toTitles as $oTitle):?>
			<tr>
				<td class="td-image">
					<?php foreach($oTitle->findDocs() as $oDoc):?>
						<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-xs',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))); ?>
					<?php endforeach;?>
				</td>
				<td class="td-text">
					<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(),new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->award_id))); ?>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
