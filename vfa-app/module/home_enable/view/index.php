<div class="well well-sm">
	<h1 class="text-center">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
</div>
<div class="row">
	<?php if (count($this->toInProgressAwards) > 0) : ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-body">
					<h4><a href="<?php echo $this->getLink('results::awardInProgress') ?>">Sélection en cours</a></h4>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if (count($this->toUserRegistredAwards) > 0) : ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading"><h3 class="panel-title">Voter</h3></div>
				<div class="panel-body">
					<?php foreach ($this->toUserRegistredAwards as $oAward) : ?>
						<a class="btn btn-block btn-lg btn-default"
							href="<?php echo $this->getLink('votes::index', array('award_id' => $oAward->getId())) ?>">
							<?php echo($oAward->toString()) ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>