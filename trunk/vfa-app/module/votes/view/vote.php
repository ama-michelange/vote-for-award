<?php if ($this->oAward): ?>
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Bulletin de vote <?php echo $this->oAward->toStringWithPrefix() ?></h3>
		</div>
		<div class="panel-body">
			<?php foreach ($this->oVote->getVoteItems() as $oVoteItem): ?>
				<div class="panel panel-inner panel-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="panel-images panel-images-col">
									<div class="panel-images-body">
										<?php foreach ($oVoteItem->getTitle()->findDocs() as $oDoc): ?>
											<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-max', null, true); ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
							<div class="col-sm-9">
								<div class="row">
									<div class="col-xs-12">
										<h2><?php echo $oVoteItem->getTitle()->toString() ?></h2>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-8"><h4>Votre note</h4></div>
									<div class="col-xs-4"><a class="btn btn-nonote btn-sm btn-block active">Aucune note</a></div>
								</div>
								<div class="row">
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block active">0</a></div>
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block">1</a></div>
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block">2</a></div>
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block">3</a></div>
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block">4</a></div>
									<div class="col-xs-2"><a class="btn btn-note btn-lg btn-block">5</a></div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<h4>Votre commentaire</h4>
										<?php
										$mess = '';
										$mess .= 'Title = ' . $oVoteItem->getTitle()->toString() . "\n";
										$mess .= 'vote_item_id = ' . $oVoteItem->vote_item_id . "\n";
										$mess .= 'vote_id = ' . $oVoteItem->vote_id . "\n";
										$mess .= 'title_id = ' . $oVoteItem->title_id . "\n";
										$mess .= 'score = ' . $oVoteItem->score . "\n";
										$mess .= 'comment = ' . $oVoteItem->comment . "\n";
										$mess .= 'modified = ' . $oVoteItem->modified . "\n";
										?>
										<textarea class="form-control" rows="3"><?php echo $mess ?></textarea>
									</div>
								</div>
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
