<div class="panel panel-info panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if (plugin_vfa::PROCESS_INTIME == $this->oRegistry->oRegin->process)  : ?>
				Inscription réalisée
			<?php else: ?>
				Inscription à valider par le correspondant
			<?php endif; ?>
		</h3>
	</div>
	<div class="panel-body panel-condensed">
		<h4>Affectation</h4>
		<dl class="dl-horizontal">
			<dt>Groupe</dt>
			<dd><?php echo $this->oRegistry->oRegin->findGroup()->toString() ?></dd>
		</dl>
		<?php if (plugin_vfa::PROCESS_INTIME == $this->oRegistry->oRegin->process)  : ?>
			<h4>Inscription</h4>
		<?php else: ?>
			<h4 class="text-warning">A valider par le correspondant</h4>
		<?php endif; ?>
		<dl class="dl-horizontal">
			<dt>Participation</dt>
			<dd><?php echo $this->oRegistry->oRegin->toStringAllAwards() ?></dd>
		</dl>
	</div>
</div>