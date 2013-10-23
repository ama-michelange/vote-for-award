<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oGroup->group_name ?></h3> 
	</div>
	<div class="panel-body">
			<h4><?php echo $this->oGroup->getTypeString() ?></h4>
			<?php if($this->toUsers):?>
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" href="#users">Utilisateurs du groupe</a>
						</h5>
					</div>
	 				<div id="users" class="collapse in">
						<table class="table table-stripped table-condensed">
							<thead>
								<tr>
									<th>Pseudo</th>
									<th>Nom</th>
									<th>Prénom</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($this->toUsers as $oUser):?>
								<tr>
									<td><a href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"><?php echo $oUser->username ?></a></td>
									<td><?php echo $oUser->last_name ?></td>
									<td><?php echo $oUser->first_name ?></td>
									<td><?php echo $oUser->email ?></td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif;?>
	</div>
</div>