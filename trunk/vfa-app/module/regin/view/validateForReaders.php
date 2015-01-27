<form action="" method="POST">
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Inscription de lecteurs : <?php echo $this->oGroup->toString() ?></h3>
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
					<!--				<dl class="dl-horizontal pull-right">-->
					<!--					<dt>Groupe</dt>-->
					<!--					<dd>--><?php //echo $this->oGroup->toString() ?><!--</dd>-->
					<!--				</dl>-->
				</div>
			</div>
			<div class="panel panel-info panel-inner">
				<div class="panel-heading">
					<h3 class="panel-title">A valider</h3>
				</div>
				<?php if (null != $this->oRegin && count($this->tReginUsers) > 0) : ?>
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
							<tr>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
								<th></th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Email</th>
								<th>Identifiant</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($this->tReginUsers as $oReginUsers): $oUser = $oReginUsers->findUser() ?>
								<tr id="tr_<?php echo $oReginUsers->getId() ?>">
									<td>
										<button class="btn btn-default btn-xs btn-valid-on" type="button"
												  data-id="<?php echo $oReginUsers->getId() ?>">
											<i class="glyphicon glyphicon-ok"></i>
										</button>
										<button class="btn btn-default btn-xs btn-valid-off" type="button"
												  data-id="<?php echo $oReginUsers->getId() ?>">
											<i class="glyphicon glyphicon-remove"></i>
										</button>
										<input type="hidden" name="ru_<?php echo $oReginUsers->getId() ?>" value=""/>
									</td>
									<td><i class="vaglyd glyphicon glyphicon-ok"></i></td>
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
</form>
