<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if ($this->oAward) : ?>
				<?php echo $this->oAward->toString() ?>
				<span class="pull-right"><small>Fin du prix le</small> <?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?></span>
			<?php else : ?>
				Aucun prix en cours ...
			<?php endif; ?>
		</h3>
	</div>
	<?php if ($this->toTitles) : ?>
		<div class="panel-body">
			<div class="panel panel-inner panel-info">
				<div class="panel-body">
					<?php foreach ($this->toTitles as $oTitle): ?>
						<div class="panel-images panel-images-upper panel-images-space">
							<div class="panel-images-body">
								<?php foreach ($oTitle->findDocs() as $oDoc): ?>
									<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-lg',
										new NavLink('nominees', 'read', array('id' => $oTitle->getId(), 'idSelection' => 'idAma')), true); ?>
								<?php endforeach; ?>
								<div class="panel-images-title">
									<p><?php echo $oTitle->toString(); ?></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
