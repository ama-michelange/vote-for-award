<?php if (0 == count($this->tInProgressAwards)): ?>
	<div class="panel panel-root panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title">Impossible de créer la permission des inscriptions !</h3>
		</div>
		<div class="panel-body">
			<p><?php echo $this->text ?></p>
			<p>Les inscriptions ne sont pas encore possibles.</p>
		</div>
	</div>
<?php elseif (0 == count($this->tGroupRegistryAwards)): ?>
	<div class="panel panel-root panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title">Impossible de créer la permission des inscriptions !</h3>
		</div>
		<div class="panel-body">
			Le groupe <strong><?php echo $this->oGroup->toString() ?></strong> n'est pas enregistré auprès d'Alices pour participer au
			prix <?php echo $this->tInProgressAwards[0]->year ?>.
		</div>
	</div>
<?php endif; ?>
