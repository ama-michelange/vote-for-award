<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oTitle->toString() ?>
			<small>( Nominé de la sélection <?php echo $this->oSelection->toString() ?> )</small>
		</h3>
	</div>
	<div class="panel-body">
		<?php if ($this->toDocs): ?>
			<?php foreach ($this->toDocs as $oDoc): ?>
				<div class="col-xs-7 col-sm-5 col-md-4 bd-show">
					<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-lg', new NavLink('docs', 'read', array('id' => $oDoc->getId())), true); ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
