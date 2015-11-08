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
							 href="<?php echo $this->getLink('votes::index') ?>">Fermer</a>
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
				<div class="panel-heading clearfix">
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
						<span style="padding-right: 5px;">Fin du prix le</span>
						<span style="padding-right: 25px;" class="panel-title"><span
								class="label label-primary"><?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?></span></span>
						<span style="padding-right: 5px;">Votes enregistrées</span>
						<span class="panel-title"><span
								class="label <?php echo $numberLabel ?>"><?php echo $nbNotes . ' / ' . $maxNotes ?></span></span>
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
													<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-min', null, true); ?>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
									<div class="col-sm-9" data-notes-group="<?php echo $oVoteItem->title_id ?>">
										<div class="row">
											<div class="col-xs-12">
												<h4><?php echo $oVoteItem->getTitle()->toString() ?></h4>
											</div>
										</div>
										<div class="row">
											<?php
											$score = $oVoteItem->score;
											if (!isset($score)) : $score = '-1'; endif; ?>
											<input type="hidden" name="no_<?php echo $oVoteItem->title_id . '_' . $oVoteItem->vote_item_id ?>"
														 value="<?php echo $score ?>"/>

											<div class="col-xs-6 col-sm-6 col-md-5 col-lg-4">
												<h5>Vote</h5>
												<div class="btn-group btn-group-sm">
													<button type="button" class="btn btn-default btn-note" data-select-note="0">0</button>
													<button type="button" class="btn btn-default btn-note" data-select-note="1">1</button>
													<button type="button" class="btn btn-default btn-note" data-select-note="2">2</button>
													<button type="button" class="btn btn-default btn-note" data-select-note="3">3</button>
													<button type="button" class="btn btn-default btn-note" data-select-note="4">4</button>
													<button type="button" class="btn btn-default btn-note" data-select-note="5">5</button>
												</div>
												<button type="button" class="btn btn-default btn-sm btn-nonote"><i
														class="glyphicon glyphicon-remove"></i></button>
												<div><em class="note-help">&nbsp;</em></div>
											</div>
											<div class="col-xs-6 col-sm-6 col-md-5 col-lg-4">
												<h5>Note attribuée</h5>
												<div class="vote-text"><span data-note>Votre note :</span></div>
											</div>
										</div>
<!--										<div class="row">-->
<!--											<div class="col-xs-12">-->
<!--												<h4>Commentaire</h4>-->
<!--												<textarea class="form-control"-->
<!--																	name="co_--><?php //echo $oVoteItem->title_id . '_' . $oVoteItem->vote_item_id ?><!--"-->
<!--																	rows="2">--><?php //echo $oVoteItem->comment ?><!--</textarea>-->
<!--											</div>-->
<!--										</div>-->
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
