<?php if ($this->oAward): ?>
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Bulletin de vote <?php echo $this->oAward->toStringWithPrefix() ?></h3>
		</div>
		<div class="panel-body">
			<?php foreach ($this->oVote->getVoteItems() as $oVoteItem): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-inner panel-info">
							<div class="panel-body">
								<div class="panel-images panel-images-horizontal">
									<div class="panel-images-body">
										<?php foreach ($oVoteItem->getTitle()->findDocs() as $oDoc): ?>
											<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-md', null, true); ?>
										<?php endforeach; ?>
									</div>
								</div>
								<h3><?php echo $oVoteItem->getTitle()->toString() ?></h3>
								<form class="pull-left">
									<h5>Votre note</h5>
									<div>
										<a href="#" class="btn btn-default btn-lg active">0</a>
										<a href="#" class="btn btn-default btn-lg">1</a>
										<a href="#" class="btn btn-default btn-lg">2</a>
										<a href="#" class="btn btn-default btn-lg">3</a>
										<a href="#" class="btn btn-default btn-lg">4</a>
										<a href="#" class="btn btn-default btn-lg">5</a>
										<a href="#" class="btn btn-default btn-lg">Pas de note</a>
									</div>
									<h5>Votre commentaire</h5>
									<div>
										<textarea class="form-control" rows="2"></textarea>
									</div>
									<div>
										<p></p>
										<button type="button" class="btn btn-default">Valider le vote de cet album</button>
									</div>
								</form>
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
