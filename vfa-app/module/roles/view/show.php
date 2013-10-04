<div class="well well-white">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo $this->oRole->role_name ?></h3>
			<p><?php echo $this->oRole->description ?></p>
		</div>
	</div>
	<div class="row">
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle lead muted" data-toggle="collapse" href="#auths">Habilitations</a>
			</div>
			<div id="auths" class="accordion-body collapse in">
				<div class="accordion-inner bd-list">
					<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<th class="col-md-3">Modules</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->tCompleteAclModules as $module => $tActions):?>
							<tr>
								<th><h4><?php echo $module ?></h4></th>
								<td>
								<?php foreach($tActions as $key => $checkbox):?>
									<?php if ($checkbox['checked']):?> 
										<span class="label label-success"><?php echo $checkbox['action']?></span>
									<?php else :?>
										<span class="label" style="font-weight: normal;"><?php echo $checkbox['action']?></span>
									<?php endif;?>
								<?php endforeach;?>
								</td>
							</tr>
							<?php endforeach;?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>	
	</div>
</div>