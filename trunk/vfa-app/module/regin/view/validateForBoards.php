<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
	<div class="alert alert-warning clearfix">
		<p>
			<?php echo plugin_validation::show($this->tMessage, 'token') ?>
			<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('regin::validate') ?>">Fermer</a>
		</p>
	</div>
<?php else: ?>
	<form action="" method="POST">
		<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
		<input type="hidden" name="regin_id" value="<?php echo $this->oRegin->getId() ?>"/>
		<div class="panel panel-default panel-root">
			<div class="panel-heading">
				<h3 class="panel-title">Inscription de lecteurs comme Membre du <?php echo $this->oGroup->toString() ?></h3>
			</div>
			<div class="panel-body panel-condensed">
				<div class="panel panel-info panel-inner">
					<div class="panel-body panel-condensed">
						<dl class="dl-horizontal">
							<dt>Participation</dt>
							<?php foreach ($this->tAwards as $award): ?>
								<dd><?php echo $award->toString() ?></dd>
							<?php endforeach; ?>
						</dl>
					</div>
				</div>
				<div class="panel panel-info panel-inner">
					<?php if (null != $this->oRegin && count($this->tReginUsers) > 0) : ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover table-inverse">
								<thead>
								<tr>
									<th style="white-space: nowrap;" colspan="2">
										<button class="btn btn-default btn-xs btn-all-valid-on" type="button">
											<i class="glyphicon glyphicon-ok"></i>
										</button>
										<button class="btn btn-default btn-xs btn-all-valid-question" type="button">
											<i class="glyphicon glyphicon-question-sign"></i>
										</button>
										<button class="btn btn-default btn-xs btn-all-valid-off" type="button">
											<i class="glyphicon glyphicon-remove"></i>
										</button>
									</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th>Email</th>
									<th>Identifiant</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($this->tReginUsers as $oReginUsers): ?>
									<?php
									$oUser = $oReginUsers->findUser();
									$trClass = '';
									$name = 'unknown[]';
									$gly = 'question-sign';
									switch ($oReginUsers->accepted) {
										case 1:
											$trClass = 'class="info"';
											$name = 'accepted[]';
											$gly = 'ok';
											break;
										case -1:
											$trClass = 'class="warning"';
											$name = 'rejected[]';
											$gly = 'remove';
											break;
									}
									?>
									<tr id="tr_<?php echo $oReginUsers->getId() ?>" <?php echo $trClass ?>>
										<td style="white-space: nowrap;">
											<button class="btn btn-default btn-xs btn-valid-on" type="button"
													  data-id="<?php echo $oReginUsers->getId() ?>">
												<i class="glyphicon glyphicon-ok"></i>
											</button>
											<button class="btn btn-default btn-xs btn-valid-question" type="button"
													  data-id="<?php echo $oReginUsers->getId() ?>">
												<i class="glyphicon glyphicon-question-sign"></i>
											</button>
											<button class="btn btn-default btn-xs btn-valid-off" type="button"
													  data-id="<?php echo $oReginUsers->getId() ?>">
												<i class="glyphicon glyphicon-remove"></i>
											</button>
											<input type="hidden" name="<?php echo $name ?>" value="<?php echo $oReginUsers->getId() ?>"/>
										</td>
										<td><i class="vaglyd glyphicon glyphicon-<?php echo $gly ?>"></i></td>
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
					<div class="panel-footer">
						<div class="text-center">
							<button class="btn btn-default btn-lg" type="submit">
								<i class="glyphicon glyphicon-ok with-text"></i>Ok
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php endif; ?>
<?php if ($this->oRegin->openModalConfirm) : ?>
	<?php echo $this->oViewModalConfirm->show(); ?>
<?php endif; ?>