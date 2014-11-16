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
			<div class="row">
				<div class="col-md-12">
					<?php for ($i = 0; ($i < 1 && $i < count($this->toResults)); $i++) : ?>
						<?php $oResult = $this->toResults[$i]; ?>
						<?php $oTitle = $oResult->findTitle(); ?>
						<div class="panel-images panel-images-center panel-images-upper panel-images-space panel-images-info">
							<div class="panel-images-heading text-center">
								<h1 style="line-height: 0.8; font-size: 700%;"><?php echo $i + 1 ?></h1>
							</div>
							<div class="panel-images-body">
								<?php foreach ($oTitle->findDocs() as $oDoc): ?>
									<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-winner', null, true); ?>
								<?php endforeach; ?>
							</div>
							<div class="panel-images-footer text-center">
								<h3><?php echo $oTitle->toString() ?></h3>
							</div>
							<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
								<div class="panel-images-footer">
									<dl class="dl-horizontal small">
										<dt>Note moyenne</dt>
										<dd><?php echo $oResult->average ?></dd>
										<dt>Nombre de votes</dt>
										<dd><?php echo $oResult->number ?></dd>
									</dl>
								</div>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
			<div class="row">
				<?php for ($i = 1; ($i < 3 && $i < count($this->toResults)); $i++) : ?>
					<?php $oResult = $this->toResults[$i]; ?>
					<?php $oTitle = $oResult->findTitle(); ?>
					<div class="col-md-6">
						<div class="panel-images panel-images-center panel-images-upper panel-images-space panel-images-info">
							<div class="panel-images-heading text-center">
								<h1 style="line-height: 0.8; font-size: 500%;"><?php echo $i + 1 ?></h1>
							</div>
							<div class="panel-images-body">
								<?php foreach ($oTitle->findDocs() as $oDoc): ?>
									<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-md', null, true); ?>
								<?php endforeach; ?>
							</div>
							<div class="panel-images-footer text-center">
								<h3><?php echo $oTitle->toString() ?></h3>
							</div>
							<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
								<div class="panel-images-footer">
									<dl class="dl-horizontal small">
										<dt>Note moyenne</dt>
										<dd><?php echo $oResult->average ?></dd>
										<dt>Nombre de votes</dt>
										<dd><?php echo $oResult->number ?></dd>
									</dl>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endfor; ?>
			</div>
			<div class="clearfix">
				<?php for ($i = 3; $i < count($this->toResults); $i++) : ?>
					<?php $oResult = $this->toResults[$i]; ?>
					<?php $oTitle = $oResult->findTitle(); ?>
					<div class="panel-images panel-images-center panel-images-upper panel-images-space panel-images-info">
						<div class="panel-images-heading text-center">
							<h2><?php echo $i + 1 ?></h2>
						</div>
						<div class="panel-images-body">
							<?php foreach ($oTitle->findDocs() as $oDoc): ?>
								<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-sm', null, true); ?>
							<?php endforeach; ?>
						</div>
						<div class="panel-images-footer text-center">
							<h4><?php echo $oTitle->toString() ?></h4>
						</div>
						<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
							<div class="panel-images-footer">
								<dl class="dl-horizontal small">
									<dt>Note moyenne</dt>
									<dd><?php echo $oResult->average ?></dd>
									<dt>Nombre de votes</dt>
									<dd><?php echo $oResult->number ?></dd>
								</dl>
							</div>
						<?php endif; ?>
					</div>
				<?php endfor; ?>

			</div>
			<?php if (_root::getACL()->isInRole(plugin_vfa::ROLE_BOARD)) : ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-heading">Statistiques</div>
					<div class="panel-body">
						<div class="row">
							<dl class="col-md-2 text-center">
								<dt>Bulletins valides</dt>
								<dd><?php echo model_vote_stat::getInstance()->extract($this->toStats, plugin_vfa::CODE_NB_BALLOT_VALID) ?></dd>
							</dl>
							<dl class="col-md-2 text-center">
								<dt>Bulletins reçus</dt>
								<dd><?php echo model_vote_stat::getInstance()->extract($this->toStats, plugin_vfa::CODE_NB_BALLOT) ?></dd>
							</dl>
							<dl class="col-md-2 text-center">
								<dt>Lecteurs inscrits</dt>
								<dd><?php echo model_vote_stat::getInstance()->extract($this->toStats, plugin_vfa::CODE_NB_REGISTRED) ?></dd>
							</dl>
							<dl class="col-md-2 text-center">
								<dt>Groupes inscrits</dt>
								<dd><?php echo model_vote_stat::getInstance()->extract($this->toStats, plugin_vfa::CODE_NB_GROUP) ?></dd>
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
