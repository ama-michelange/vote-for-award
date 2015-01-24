<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Inscription de lecteurs</h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel panel-info panel-inner">
			<div class="panel-body panel-condensed">
				<dl class="dl-horizontal dl-lg pull-left">
					<dt>Participation au</dt>
					<?php foreach ($this->tAwards as $award): ?>
						<dd><?php echo $award->toString() ?></dd>
					<?php endforeach; ?>
				</dl>
				<dl class="dl-horizontal dl-lg pull-right">
					<dt>Groupe</dt>
					<dd><?php echo $this->oGroup->toString() ?></dd>
				</dl>
			</div>
		</div>
		<div class="panel panel-info panel-inner">
			<?php if (null != $this->oRegin && count($this->tReginUsers) > 0) : ?>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>Nom</th>
							<th>Prénom</th>
							<th>Email</th>
							<th>Identifiant</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($this->tReginUsers as $oReginUsers): $oUser = $oReginUsers->findUser() ?>
							<tr>
								<td><?php echo $oUser->last_name ?></td>
								<td><?php echo $oUser->first_name ?></td>
								<td><?php echo $oUser->email ?></td>
								<td><?php echo $oUser->login ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="panel-body">
					<h4>Aucune inscription à valider !</h4>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
