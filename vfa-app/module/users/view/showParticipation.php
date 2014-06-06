<div class="panel panel-default panel-inner">
	<div class="panel-body">
		<div class="row">
			<?php if ($this->toParticipationAwards): $label = 'Participation'; ?>
				<?php foreach ($this->toParticipationAwards as $oAward): ?>
					<div class="col-sm-3 col-md-2 col-lg-2 view-label"><?php echo $label ?></div>
					<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $oAward->toString() ?></div>
					<?php $label = ''; endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>