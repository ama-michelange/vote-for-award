<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="text-muted">
				<?php echo plugin_BsHtml::showNavLabel('Sélection '.$this->oSelection->toString(),new NavLink('selections', 'read', array('id'=>$this->oSelection->selection_id))); ?>
			</span>
			<span class="text-info">&gt;</span>
			Nominés
		</h3>
	</div>
	<?php if($this->toTitles):?>
		<table class="table table-striped table-condensed table-image">
			<tbody>
				<?php foreach($this->toTitles as $oTitle):?>
				<tr>
					<td class="td-image">
						<?php foreach($oTitle->findDocs() as $oDoc):?>
							<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-xs',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idSelection'=>$this->oSelection->selection_id))); ?>
						<?php endforeach;?>
					</td>
					<td class="td-text">
						<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(),new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idSelection'=>$this->oSelection->selection_id))); ?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="panel-body">
			<h4>Aucun nominé !</h4>
		</div>
	<?php endif;?>
</div>
