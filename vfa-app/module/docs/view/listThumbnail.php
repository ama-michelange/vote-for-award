<div class="panel panel-default panel-root">
	<?php if ($this->tDocs): ?>
		<div class="panel-body">
			<?php foreach ($this->tDocs as $oDoc): ?>
				<div class="panel-images">
					<div class="panel-images-body">
						<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-sm', new NavLink('docs', 'read', array('id' => $oDoc->getId()))); ?>
						<div class="panel-images-title">
							<p>
								<strong><?php echo plugin_BsHtml::showNavLabel($oDoc->title, new NavLink('docs', 'read', array('id' => $oDoc->getId()))); ?></strong>
							</p>
							<?php if ($oDoc->toStringNumberProperTitle()): ?>
								<p>
									<small><?php echo $oDoc->toStringNumberProperTitle() ?></small>
								</p>
							<?php else: ?>
								<p>&nbsp;</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
