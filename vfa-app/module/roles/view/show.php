<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oRole->role_name ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel panel-default panel-inner">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2 col-md-2 col-lg-1 view-label">Nom</div>
					<div class="col-sm-10 col-md-10 col-lg-11 view-value"><?php echo $this->oRole->role_name ?></div>
				</div>
				<div class="row">
					<div class="col-sm-2 col-md-2 col-lg-1 view-label">Description</div>
					<div class="col-sm-10 col-md-10 col-lg-11 view-value"><?php echo $this->oRole->description ?></div>
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-inner">
			<div class="panel-heading">
				<h5 class="panel-title">
					Habilitations <a class="pull-right accordion-toggle" data-toggle="collapse" href="#auths"><i
							data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
				</h5>
			</div>
			<div id="auths" class="collapse in">
				<table class="table table-striped table-condensed">
					<thead>
					<tr>
						<th class="col-md-1">Modules</th>
						<th>Actions habilitées</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($this->tCompleteAclModules as $module => $tActions): ?>
						<tr class="double">
							<td><?php echo $module ?></td>
							<td>
								<?php foreach ($tActions as $key => $checkbox): ?>
									<?php if ($checkbox['checked']): ?>
										<span class="label label-success"><?php echo $checkbox['action'] ?></span>
									<?php else : ?>
										<span class="label label-default label-disabled"><?php echo $checkbox['action'] ?></span>
									<?php endif; ?>
								<?php endforeach; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
