<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Inscription des correspondants</h3>
	</div>

	<?php if ($this->tRegins): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-condensed">
				<thead>
				<tr>
					<th>Code</th>
					<th>Groupe</th>
					<th>Prix</th>
					<th>Limite</th>
					<th>Etat</th>
					<!--					<th>Type</th>-->
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tRegins as $oRegin): ?>
					<?php
					switch ($oRegin->type) {
						case plugin_vfa::TYPE_BOARD:
							$typeColor = 'label label-danger';
							break;
						case plugin_vfa::TYPE_RESPONSIBLE:
							$typeColor = 'label label-warning';
							break;
						case plugin_vfa::TYPE_READER:
							$typeColor = '';
							break;
						default:
							$typeColor = '';
							break;
					}
					switch ($oRegin->state) {
						case plugin_vfa::STATE_OPEN:
							$stateColor = 'label-info';
							$stateTip = 'L\'invitation est ouverte. Le correspondant ne l\'a pas encore utilisée';
							break;
						case plugin_vfa::STATE_ACCEPTED:
							$stateColor = 'label-success';
							$stateTip = $oRegin->email . ' a accepté l\'invitation.';
							break;
						default:
							$stateColor = '';
							$stateTip = '';
							break;
					}
					?>
					<tr>
						<td>
							<a href="<?php echo $this->getLink('regin::openedResponsible', array('id' => $oRegin->getId())) ?>">
								<?php echo $oRegin->code ?>
							</a>
						</td>
						<td>
							<?php
							$oGroup = $oRegin->findGroup();
							echo $oGroup->group_name;
							?>
						</td>
						<td>
							<?php
							$tAwards = $oRegin->findAwards();
							$i = 0;
							foreach ($tAwards as $award) {
								if ($i > 0) {
									echo '<br>';
								};
								echo $award->toString();
								$i++;
							}
							?>
						</td>
						<td><?php echo plugin_vfa::toStringDateShow($oRegin->process_end) ?></span></td>
						<td>
							<span class="label <?php echo $stateColor ?>" data-rel="tooltip"
										data-original-title="<?php echo $stateTip ?>"><?php echo $oRegin->showState() ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<h4>Aucune permission d'inscriptions en cours</h4>

			<p>Pour créer une permission d'inscriptions pour un correspondant
				<i class="glyphicon glyphicon-hand-right with-left-text with-text"></i>
				<a class="btn btn-default" href="<?php echo $this->getLink('regin::openResponsible') ?>">Créer</a>
			</p>
		</div>
	<?php endif; ?>
</div>
