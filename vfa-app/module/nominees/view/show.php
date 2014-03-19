<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="text-muted">
				<?php echo plugin_BsHtml::showNavLabel('Sélection '.$this->oSelection->toString(),new NavLink('selections', 'read', array('id'=>$this->oSelection->selection_id))); ?>
			</span>
			<span class="text-info">&gt;</span>
			Nominé : <?php echo $this->oTitle->toString()?>
		</h3>
	</div>
	<div class="panel-body">
		<?php if($this->toDocs):?>
			<?php foreach($this->toDocs as $oDoc):?>
				<div class="col-xs-7 col-sm-5 col-md-4 bd-show">
					<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-lg',new NavLink('docs', 'read', array('id'=>$oDoc->getId())),true); ?>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
