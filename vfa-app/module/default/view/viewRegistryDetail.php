<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Inscription
			<?php if ($this->oRegin->type == plugin_vfa::TYPE_READER): ?>
				d'un lecteur
			<?php elseif ($this->oRegin->type == plugin_vfa::TYPE_BOARD): ?>
				d'un lecteur comme membre du Comité de sélection
			<?php elseif ($this->oRegin->type == plugin_vfa::TYPE_RESPONSIBLE): ?>
				d'un correspondant du groupe <strong><?php echo $this->oGroup->toString() ?></strong>
			<?php endif; ?>
		</h3>
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
					<?php if ($this->oRegin->type == plugin_vfa::TYPE_RESPONSIBLE): ?>
						<dt>Correspondant</dt>
						<dd><span class="glyphicon glyphicon-check"></span></dd>
					<?php endif; ?>
				</dl>
			</div>
			<div class="col-sm-6 col-md-6">
				<dl class="dl-horizontal dl-lg">
					<dt>Code</dt>
					<dd><?php echo $this->oRegin->code ?></dd>
					<dt>Créé par</dt>
					<dd><?php echo $this->oCreatedUser->toStringFirstLastName() ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>
