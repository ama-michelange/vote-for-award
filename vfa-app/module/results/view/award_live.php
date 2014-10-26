<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if ($this->oAward) : ?>
				<small>Résultat intermédiaire</small> <?php echo $this->oAward->toString() ?>
			<?php else : ?>
				Aucun prix en cours ...
			<?php endif; ?>
		</h3>
	</div>
	<?php if ($this->oAward) : ?>
		<div class="panel-body">
			<div class="panel panel-inner panel-info">
				<div class="panel-body">
					Stats
					Inscrits
					Participation
				</div>
			</div>
			<?php
				$i = 0;
				foreach ($this->toResults as $oResult):
					$oTitle = $oResult->findTitle();
			?>
			<div class="panel panel-inner panel-info">
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-2">
							<h1 class="pull-right"><?php echo ++$i ?></h1>
						</div>
						<div class="col-xs-10">
							<div class="panel-images panel-images-horizontal">
								<div class="panel-images-body">
									<?php foreach ($oTitle->findDocs() as $oDoc): ?>
										<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-sm', null, true); ?>
									<?php endforeach; ?>
								</div>
							</div>
							<h2><?php echo $oTitle->toString() ?></h2>
							Note moyenne : <?php echo $oResult->average ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<h4>... pas de résultat intermédiaire</h4>
		</div>
	<?php endif; ?>
</div>
