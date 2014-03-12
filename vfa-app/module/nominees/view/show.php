<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?> : <?php echo $this->oTitle->toString()?>
		</h3>
	</div>
	<div class="panel-body">
		<?php if($this->toDocs):?>
			<?php foreach($this->toDocs as $oDoc):?>
				<div class="col-xs-9 col-sm-5 col-md-5 bd-show">
					<h4>
						<?php echo plugin_BsHtml::showNavLabel($oDoc->title, new NavLink('docs', 'read', array( 'id'=>$oDoc->getId()))); ?>
					</h4>
					<p><?php echo $oDoc->toStringNumberProperTitle() ?></p>
					<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-responsive',new NavLink('docs', 'read', array('id'=>$oDoc->getId()))); ?>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
