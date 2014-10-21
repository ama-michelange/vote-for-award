<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
		<div class="panel panel-default panel-root">
			<div class="panel-heading">
				<h3 class="panel-title">Bulletin de vote</h3>
			</div>
			<div class="panel-body">
				<div class="alert alert-warning clearfix">
					<p><?php echo plugin_validation::show($this->tMessage, 'token') ?>
						<a class="btn btn-sm btn-warning pull-right"
							href="<?php echo $this->getLink('roles::index') ?>">Fermer</a>
					</p>
				</div>
			</div>
		</div>
	<?php else: ?>
		<?php if ($this->oAward): ?>
			<input type="hidden" name="vote_id" value="<?php echo $this->oVote->vote_id ?>"/>
			<input type="hidden" name="award_id" value="<?php echo $this->oVote->award_id ?>"/>
			<input type="hidden" name="user_id" value="<?php echo $this->oVote->user_id ?>"/>
			<div class="panel panel-default panel-root">
				<div class="panel-heading">
					<span class="panel-title">Bulletin de vote <?php echo $this->oAward->toStringWithPrefix() ?></span>
					<span class="pull-right">
						<?php
						$maxNotes = count($this->oVote->getVoteItems());
						if (!$this->oVote->number) {
							$nbNotes = 0;
						} else {
							$nbNotes = $this->oVote->number;
						}
						$numberLabel = 'label-primary';
						if ($this->oAward->type == plugin_vfa::TYPE_AWARD_READER) {
							if ($nbNotes < 7) {
								$numberLabel = 'label-warning';
							}
						}
						?>
						<span style="padding-right: 5px;">Votes enregistrées</span>
						<span class="panel-title"><span class="label <?php echo $numberLabel ?>"><?php echo $nbNotes . ' / ' . $maxNotes ?></span></span>
					</span>
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
									<div class="col-sm-9" data-notes-group="<?php echo $oVoteItem->title_id ?>">
										<div class="row">
											<div class="col-xs-12">
												<h2><?php echo $oVoteItem->getTitle()->toString() ?></h2>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-7 col-sm-8" data-note><h4>Votre note : </h4></div>
											<div class="col-xs-5 col-sm-4"><a class="btn btn-nonote btn-sm btn-block">Annuler ce vote</a></div>
										</div>
										<div class="row">
											<?php
											$score = $oVoteItem->score;
											if (!isset($score)) : $score = '-1'; endif; ?>
											<input type="hidden" name="no_<?php echo $oVoteItem->title_id . '_' . $oVoteItem->vote_item_id ?>"
													 value="<?php echo $score ?>"/>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="0">0</a><span class="hidden-xs">Sans intérêt</span></div>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="1">1</a><span class="hidden-xs">Décevant</span></div>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="2">2</a><span class="hidden-xs">Moyen</span></div>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="3">3</a><span class="hidden-xs">Bon</span></div>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="4">4</a><span class="hidden-xs">Très bon</span></div>
											<div class="col-xs-2" style="text-align: center"><a class="btn btn-note btn-lg btn-block" data-select-note="5">5</a><span class="hidden-xs">Excellent</span></div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<h4>Commentaire</h4>
												<textarea class="form-control"
															 name="co_<?php echo $oVoteItem->title_id . '_' . $oVoteItem->vote_item_id ?>"
															 rows="2"><?php echo $oVoteItem->comment ?></textarea>
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
	<?php endif; ?>
</form>