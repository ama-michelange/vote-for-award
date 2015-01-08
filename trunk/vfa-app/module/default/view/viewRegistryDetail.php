<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Inscription d'un lecteur</h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<dl class="dl-horizontal dl-lg">
					<dt>
					<h4>Participation</h4>
					</dt>
					<dd>
						<h4>
							<?php $comma = false; ?>
							<?php foreach ($this->tAwards as $award): ?>
								<?php if (true == $comma): ?>
									,
								<?php endif; ?>
								<span class="text-nowrap"><?php echo $award->toString() ?></span>
								<?php $comma = true; ?>
							<?php endforeach; ?>
						</h4>
					</dd>
					<dt>Affectation</dt>
					<dd><?php echo $this->oGroup->toString() ?></dd>
				</dl>
			</div>
			<div class="col-sm-6 col-md-6">
				<dl class="dl-horizontal dl-lg">
					<dt>Code</dt>
					<dd><?php echo $this->oRegin->code ?></dd>
					<dt>Créé par</dt>
					<dd><?php echo $this->oCreatedUser->toStringPublic() ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>
