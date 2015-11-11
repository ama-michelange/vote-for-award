<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Permission : <?php echo $this->oRegin->code ?></h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal dl-lg">
			<dt>Code d'inscription</dt>
			<dd><?php echo $this->oRegin->code ?></dd>
		</dl>

		<?php
		if (plugin_vfa::TYPE_BOARD == $this->oRegin->type) {
			$textPour = 'Inscription des membres du comité';
			$textValidateur = 'organisateur';
			$textValid = 'l\'';
			$textNotValid = 'de l\'';
		}	elseif (plugin_vfa::TYPE_RESPONSIBLE == $this->oRegin->type) {
			$textPour = 'Inscription d\'un correspondant';
			$textValidateur = 'organisateur';
			$textValid = 'l\'';
			$textNotValid = 'de l\'';
		} else {
			$textPour = 'Inscription de lecteurs';
			$textValidateur = 'correspondant';
			$textValid = 'le';
			$textNotValid = 'du ';
		}
		?>

		<dl class="dl-horizontal dl-lg">
			<dt>Pour</dt>
			<dd><?php echo $textPour ?></dd>
			<dt>Affectation au groupe</dt>
			<dd><?php echo $this->oGroup->toString() ?></dd>
			<dt>Participation</dt>
			<?php foreach ($this->tAwards as $award): ?>
				<dd><?php echo $award->toString() ?></dd>
			<?php endforeach; ?>
		</dl>
		<dl class="dl-horizontal dl-lg">
			<dt>Limite des inscriptions</dt>
			<dd><?php echo plugin_vfa::toStringDateShow($this->oRegin->process_end) ?></dd>
			<dt>Processus d'inscription</dt>
			<?php if (plugin_vfa::PROCESS_INTIME == $this->oRegin->process) : ?>
				<dd><span class="text-warning">Inscription sans validation <?php echo $textNotValid . $textValidateur ?></span>
				</dd>
			<?php else : ?>
				<dd>Inscription validée par <?php echo $textValid . $textValidateur ?></dd>
			<?php endif; ?>
		</dl>
	</div>
</div>
