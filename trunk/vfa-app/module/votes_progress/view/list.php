<?php if ($this->oAward): ?>
	<div class="panel panel-default panel-root">
		<div class="panel-body panel-condensed">
			<dl class="pull-left dl-sep">
				<dt><?php echo $this->oAward->getTypeString() ?></dt>
				<dd><?php echo $this->oAward->toStringName() ?></dd>
			</dl>
			<?php if ($this->oGroup): ?>
				<dl class="pull-left dl-sep">
					<dt>Groupe</dt>
					<dd><?php echo $this->oGroup->toString() ?></dd>
				</dl>
				<?php
				$nbInscrit = model_award::getInstance()->countUser($this->oAward->getId(), $this->oGroup->getId());
				$nbBull = model_vote::getInstance()->countAllBallots($this->oAward->getId(), $this->oGroup->getId());
				$nbValidBull = model_vote::getInstance()
					->countValidBallots($this->oAward->getId(), $this->oAward->type, $this->oGroup->getId());
				$part = 0.0;
				$partValid = 0.0;
				if ($nbInscrit > 0) {
					$part = ($nbBull / $nbInscrit) * 100.0;
					$partValid = ($nbValidBull / $nbInscrit) * 100.0;
				}
				?>
				<dl class="pull-left dl-sep">
					<dt>Inscrits</dt>
					<dd><span class="pull-right"><?php echo $nbInscrit ?></span></dd>
				</dl>
				<dl class="pull-left dl-sep">
					<dt>Bulletins</dt>
					<dd><span class="pull-right"><?php echo $nbBull ?></span></dd>
				</dl>
				<dl class="pull-left dl-sep">
					<dt>Bulletins valides</dt>
					<dd><span class="pull-right"><?php echo $nbValidBull ?></span></dd>
				</dl>
				<dl class="pull-left dl-sep">
					<dt>Participations</dt>
					<dd><span class="pull-right"> <?php echo sprintf('%01.2f', $part) ?> %</span></dd>
				</dl>
				<dl class="pull-left dl-sep">
					<dt>Exprimés</dt>
					<dd><span class="pull-right"> <?php echo sprintf('%01.2f', $partValid) ?> %</span></dd>
				</dl>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<div class="panel panel-default panel-root">
	<?php if (null != $this->oAward && count($this->tUsers) > 0) : ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Votes enregistrées</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<tr>
						<td><?php echo $oUser->last_name ?></td>
						<td><?php echo $oUser->first_name ?></td>
						<td><?php echo $oUser->findVote($this->oAward->getId())->number ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<?php if (!$this->oAward): ?>
				<h4>Aucun prix en cours !</h4>
			<?php else: ?>
				<h4>Aucun lecteur inscrit !</h4>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
