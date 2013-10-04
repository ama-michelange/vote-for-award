<div class="well well-white well-small">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo $this->oGroup->group_name ?></h3>
			<p><strong><?php echo $this->oGroup->getTypeString() ?></strong></p>
			<?php if($this->toUsers):?>
			<div class="row">
				<div class="accordion-group col-md-12">
					<div class="accordion-heading">
						<a class="accordion-toggle lead muted" data-toggle="collapse" href="#titles">Utilisateurs du groupe</a>
					</div>
					<div id="titles" class="accordion-body collapse in">
						<div class="accordion-inner">
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
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>