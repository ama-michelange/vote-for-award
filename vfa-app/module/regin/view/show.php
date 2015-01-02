<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oRegin->code ?></h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal dl-lg">
			<dt>Code d'inscription</dt>
			<dd><?php echo $this->oRegin->code ?></dd>
		</dl>
		<dl class="dl-horizontal dl-lg">
			<dt>Pour</dt>
			<dd>Inscription de lecteurs</dd>
			<dt>Affectation au groupe</dt>
			<dd><?php echo $this->oGroup->toString() ?></dd>
			<dt>Inscription au</dt>
			<?php foreach ($this->tAwards as $award): ?>
				<dd><?php echo $award->toString() ?></dd>
			<?php endforeach; ?>
		</dl>
		<dl class="dl-horizontal dl-lg">
			<dt>Limite des inscriptions</dt>
			<dd><?php echo plugin_vfa::toStringDateShow($this->oRegin->process_end) ?></dd>
			<dt>Processus d'inscription</dt>
			<?php if (plugin_vfa::PROCESS_INTIME == $this->oRegin->process) : ?>
				<dd><span  class="text-warning">Inscription sans validation du correspondant</span></dd>
			<?php else :?>
				<dd>Inscription validée par le correspondant</dd>
			<?php endif; ?>
		</dl>
	</div>
</div>