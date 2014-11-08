<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if ($this->oAward) : ?>
				<?php echo $this->oAward->toString() ?>
			<?php else : ?>
				Aucun prix ...
			<?php endif; ?>
		</h3>
	</div>
	<?php if ($this->oAward) : ?>
		<div class="panel-body">
			<?php for ($i = 0; ($i < 1 && $i < count($this->toResults)); $i++) : ?>
				<?php $oResult = $this->toResults[$i]; ?>
				<?php $oTitle = $oResult->findTitle(); ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
								<h1 class="pull-right" style="line-height: 0.8; font-size: 700%;"><?php echo $i + 1 ?></h1>
							</div>
							<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
								<div class="panel-images panel-images-horizontal">
									<div class="panel-images-body">
										<?php foreach ($oTitle->findDocs() as $oDoc): ?>
											<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-winner', null, true); ?>
										<?php endforeach; ?>
									</div>
								</div>
								<h1><?php echo $oTitle->toString() ?></h1>
								<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
									<p>Note moyenne : <?php echo $oResult->average ?><br/>
										Nombre de votes : <?php echo $oResult->number ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endfor; ?>

			<div class="row">
				<?php for ($i = 1; ($i < 3 && $i < count($this->toResults)); $i++) : ?>
					<?php $oResult = $this->toResults[$i]; ?>
					<?php $oTitle = $oResult->findTitle(); ?>
					<div class="col-md-6">
						<div class="panel panel-inner panel-info">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
										<h1 class="pull-right" style="line-height: 0.8; font-size: 500%;"><?php echo $i + 1 ?></h1>
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
										<div class="panel-images panel-images-horizontal">
											<div class="panel-images-body">
												<?php foreach ($oTitle->findDocs() as $oDoc): ?>
													<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-md', null, true); ?>
												<?php endforeach; ?>
											</div>
										</div>
										<h3><?php echo $oTitle->toString() ?></h3>
										<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
											<p>Note moyenne : <?php echo $oResult->average ?><br/>
												Nombre de votes : <?php echo $oResult->number ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endfor; ?>
			</div>

			<div class="row">
				<?php for ($i = 3; $i < count($this->toResults); $i++) : ?>
					<?php $oResult = $this->toResults[$i]; ?>
					<?php $oTitle = $oResult->findTitle(); ?>
					<div class="col-md-4">
						<div class="panel panel-inner panel-info">
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
										<h1 class="pull-right" style="line-height: 0.8; font-size: 200%;"><?php echo $i + 1 ?></h1>
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
										<div class="panel-images panel-images-horizontal">
											<div class="panel-images-body">
												<?php foreach ($oTitle->findDocs() as $oDoc): ?>
													<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-sm', null, true); ?>
												<?php endforeach; ?>
											</div>
										</div>
										<h4><?php echo $oTitle->toString() ?></h4>
										<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
											<p class="small">Note moyenne : <?php echo $oResult->average ?><br/>
												Nombre de votes : <?php echo $oResult->number ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endfor; ?>
			</div>


			<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-heading">Participations</div>
					<div class="panel-body">
						<div class="row">
							<dl class="col-md-2 text-center">
								<dt>Inscrits</dt>
								<dd><?php echo model_award::getInstance()->countUser($this->oAward->getId()) ?></dd>
							</dl>
							<dl class="col-md-2 text-center">
								<dt>Bulletins reçus</dt>
								<dd><?php echo model_vote::getInstance()->countUser($this->oAward->getId()) ?></dd>
							</dl>
							<dl class="col-md-2 text-center">
								<dt>Lecteurs</dt>
								<dd><?php echo model_vote::getInstance()
										->countUserWithValidVote($this->oAward->getId(), $this->oAward->type) ?></dd>
							</dl>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<h4>... pas de résultat</h4>
		</div>
	<?php endif; ?>
</div>
