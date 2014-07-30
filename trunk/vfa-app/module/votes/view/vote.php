<?php if ($this->oAward): ?>
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Bulletin de vote <?php echo $this->oAward->toStringWithPrefix() ?></h3>
		</div>
		<div class="panel-body">
			<?php foreach ($this->oVote->getVoteItems() as $oVoteItem): ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-body">
						<div class="panel-images panel-images-horizontal">
							<div class="panel-images-body">
								<?php foreach ($oVoteItem->getTitle()->findDocs() as $oDoc): ?>
									<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-md', null, true); ?>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="pull-left">
							<h3><?php echo $oVoteItem->getTitle()->toString() ?></h3>
							<h4>Votre note</h4>
							<div>
								<a href="#" class="btn btn-default btn-lg btn-margin active">0</a>
								<a href="#" class="btn btn-default btn-lg btn-margin">1</a>
								<a href="#" class="btn btn-default btn-lg btn-margin">2</a>
								<a href="#" class="btn btn-default btn-lg btn-margin">3</a>
								<a href="#" class="btn btn-default btn-lg btn-margin">4</a>
								<a href="#" class="btn btn-default btn-lg btn-margin">5</a>
								<a href="#" class="btn btn-default">Abstention</a>
							</div>
							<h4>Votre commentaire</h4>
							<textarea class="form-control" rows="3"></textarea>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php foreach ($this->oVote->getVoteItems() as $oVoteItem): ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3 center-block">
								<div class="panel-images panel-images-col">
									<div class="panel-images-body">
										<?php foreach ($oVoteItem->getTitle()->findDocs() as $oDoc): ?>
											<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-md', null, true); ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
							<div class="col-md-9">
								<h3><?php echo $oVoteItem->getTitle()->toString() ?></h3>
								<h4>Votre note</h4>
								<div>
									<a href="#" class="btn btn-default btn-lg btn-margin active">0</a>
									<a href="#" class="btn btn-default btn-lg btn-margin">1</a>
									<a href="#" class="btn btn-default btn-lg btn-margin">2</a>
									<a href="#" class="btn btn-default btn-lg btn-margin">3</a>
									<a href="#" class="btn btn-default btn-lg btn-margin">4</a>
									<a href="#" class="btn btn-default btn-lg btn-margin">5</a>
									<a href="#" class="btn btn-default">Abstention</a>
								</div>
								<h4>Votre commentaire</h4>
								<textarea class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php else: ?>
	<div class="panel-body">
		<h4>Aucun prix en cours pour voter !</h4>
	</div>
<?php endif; ?>
