<?php if (0 == count($this->tInProgressAwards)): ?>
	<div class="panel panel-root panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title">Ouverture des inscriptions impossible !</h3>
		</div>
		<div class="panel-body">
			Aucun prix n'est ouvert ! Les inscriptions ne sont pas encore possibles.
		</div>
	</div>
<?php elseif (0 == count($this->tGroupRegistryAwards)): ?>
	<div class="panel panel-root panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title">Ouverture des inscriptions impossible !</h3>
		</div>
		<div class="panel-body">
			Le groupe <strong><?php echo $this->oReaderGroup->toString() ?></strong> n'est pas enregistré auprès d'Alices pour participer au
			prix <?php echo $this->tInProgressAwards[0]->year ?>.
		</div>
	</div>
<?php endif; ?>
