<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if ($this->oAward) : ?>
				<?php echo $this->oAward->toString() ?>
			<?php else : ?>
				Aucun prix en cours ...
			<?php endif; ?>
		</h3>
	</div>
	<?php if ($this->oAward) : ?>
		<div class="panel-body">
			<div class="panel panel-inner panel-info">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-2">
							<p>Date de fin de prix</p>

							<p><?php echo $this->oAward->end_date ?></p>
						</div>
						<div class="col-md-2 center-block">
							<p>Inscrits</p>

							<p class="center-block"><?php echo model_award::getInstance()->countUser($this->oAward->getId()) ?></p>
						</div>
						<div class="col-md-2">
							<p>Bulletins</p>

							<p><?php echo model_vote::getInstance()->countAllBallots($this->oAward->getId()) ?></p>
						</div>
						<div class="col-md-2">
							<p>Bulletins valides</p>

							<p><?php echo model_vote::getInstance()->countValidBallots($this->oAward->getId(), $this->oAward->type) ?></p>
						</div>
					</div>
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
							<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
								<h1 class="pull-right" style="line-height: 0.8; font-size: 700%;"><?php echo ++$i ?></h1>
							</div>
							<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
								<div class="panel-images panel-images-horizontal">
									<div class="panel-images-body">
										<?php foreach ($oTitle->findDocs() as $oDoc): ?>
											<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-sm', null, true); ?>
										<?php endforeach; ?>
									</div>
								</div>
								<h2><?php echo $oTitle->toString() ?></h2>

								<p>Note moyenne : <?php echo $oResult->average ?><br/>
									Nombre de votes : <?php echo $oResult->number ?></p>
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
