<div class="jumbotron">
	<h1 class="text-center">Prix de la BD <span class="text-nowrap">Inter CE ALICES</span></h1>
	<h1 class="text-center">
		<small>Bureau de votes</small>
	</h1>
</div>
<?php if (count($this->toUserRegistredAwards) > 0) : ?>
	<div class="panel panel-info">
		<div class="panel-heading"><h3 class="panel-title">Voter</h3></div>
		<div class="panel-body">
			<div class="row">
				<?php foreach ($this->toUserRegistredAwards as $oAward) : ?>
					<div class="col-md-4">
						<div class="thumbnail">
							<div class="caption text-center">
								<h3>
									<a href="<?php echo $this->getLink('votes::index', array('award_id' => $oAward->getId())) ?>">
										<?php echo($oAward->toString()) ?></a>
								</h3>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<?php if (count($this->toInProgressAwards) > 0) : ?>
		<div class="panel panel-info">
			<div class="panel-heading"><h3 class="panel-title">Prix en cours</h3></div>
			<div class="panel-body">
				<?php foreach ($this->toInProgressAwards as $oAward) : ?>
					<h4><?php echo($oAward->toString()) ?></h4>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
