<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oGroup->group_name ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel panel-default panel-inner">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2 col-md-1 view-label">Nom</div>
					<div class="col-sm-10 col-md-11 view-value"><?php echo $this->oGroup->group_name ?></div>
				</div>
				<div class="row">
					<div class="col-sm-2 col-md-1 view-label">Type</div>
					<div class="col-sm-10 col-md-11 view-value"><?php echo $this->oGroup->getI18nStringType() ?></div>
				</div>
			</div>
		</div>
		<?php if($this->toUsers):?>
			<div class="panel panel-default panel-inner">
			<div class="panel-heading">
				<h5 class="panel-title">
					Utilisateurs du groupe <a class="pull-right accordion-toggle" data-toggle="collapse"
						href="#users"><i data-chevron="collapse" class="glyphicon glyphicon-collapse-up"></i></a>
				</h5>
			</div>
			<div id="users" class="collapse in">
				<div class="table-responsive">
					<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<?php if(_root::getACL()->permit('users::read')):?>
									<th></th>
								<?php endif;?>
								<th>Identifiant</th>
								<th>Email</th>
								<th>Nom</th>
								<th>Prénom</th>
							</tr>
						</thead>
						<tbody>
								<?php foreach($this->toUsers as $oUser):?>
								<tr>
									<?php if(_root::getACL()->permit('users::read')):?>
										<td><a rel="tooltip" data-original-title="Voir <?php echo $oUser->login ?>"
									href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"><i
										class="glyphicon glyphicon-eye-open"></i></a></td>
									<?php endif;?>
									<td><?php echo $oUser->login ?></td>
									<td><?php echo $oUser->email ?></td>
									<td><?php echo $oUser->last_name ?></td>
									<td><?php echo $oUser->first_name ?></td>
							</tr>
								<?php endforeach;?>
							</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>