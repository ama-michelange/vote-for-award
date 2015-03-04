<div class="well well-sm">
	<h1 class="text-center">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
</div>
<div class="row">
	<?php if (count($this->toInProgressAwards) > 0) : ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading"><h3 class="panel-title">Voir</h3></div>
				<div class="panel-body panel-condensed">
					<ul class="list-group">
						<li class="list-group-item">
							<a href="<?php echo $this->getLink('results::awardInProgress') ?>">
								Sélection <?php echo $this->toInProgressAwards[0]->year ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if (count($this->toUserRegistredAwards) > 0) : ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading"><h3 class="panel-title">Voter</h3></div>
				<div class="panel-body panel-condensed">
					<ul class="list-group">
						<?php foreach ($this->toUserRegistredAwards as $oAward) : ?>
							<li class="list-group-item">
								<a href="<?php echo $this->getLink('votes::index', array('award_id' => $oAward->getId())) ?>">
									<?php echo($oAward->toString()) ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>