<form action="" method="POST" >
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="role_id" value="<?php echo $this->oRole->role_id ?>" />
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->textTitle ?></h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('roles::index') ?>">Fermer</a></p>
			</div>		
		</div>			
		<?php else:?>
		<div class="panel-body">
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'role_name')?>">
				<label for="inputName">Nom
					<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="Le nom du rôle">
						<i class="glyphicon glyphicon-info-sign"></i>
					</span>
				</label>
				<input class="form-control" type="text" id="inputName" name="role_name" value="<?php echo $this->oRole->role_name ?>" />
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'role_name')?></span>
			</div>
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'description')?>">
				<label for="inputDescription">Description
					<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="La description du rôle">
						<i class="glyphicon glyphicon-info-sign"></i>
					</span>
				</label>
				<textarea class="form-control" id="inputDescription" name="description"><?php echo $this->oRole->description ?></textarea>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'description')?></span>
			</div>
			<div class="form-group">
				<label>
					<a class="accordion-toggle" data-toggle="collapse" href="#acl">Habilitations</a>
					<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="Les habilitations du rôle. Cliquez sur le libellé pour fermer ou ouvrir le tableau des habilitations.">
						<i class="glyphicon glyphicon-info-sign"></i>
					</span>
					<a class="accordion-toggle" data-toggle="collapse" href="#acl"><i class="glyphicon glyphicon-chevron-up"></i></a>
				</label>
				<div id="acl" class="controls collapse in">			
					<?php if($this->tCompleteAclModules):?>
					<table class="table table-hover" style="background-color: white;">
						<thead>
							<tr>
								<td class="col-md-1">Modules</td>
								<td>Actions</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->tCompleteAclModules as $module => $tActions):?>
							<tr>
								<th><?php echo $module ?></th>
								<td>
								<?php foreach($tActions as $key => $checkbox):?>
									<label class="checkbox-inline" style="margin-right: 10px; margin-left: 0;">
										<input type="checkbox" name="<?php echo $key ?>" value="<?php echo $key ?>" <?php if ($checkbox['checked']) echo 'checked'; ?>>
										<?php if ($checkbox['checked']): ?>
											<span class="label label-success"><?php echo $checkbox['action']?></span>
										<?php else :?>
											<?php echo $checkbox['action']?>
										<?php endif;?>					
									</label>
								<?php endforeach;?>
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<?php if(trim($this->oRole->role_id)==false):?>
				<a class="btn btn-default" href="<?php echo $this->getLink('roles::index') ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
				<a class="btn btn-default" href="<?php echo $this->getLink('roles::read',array('id'=>$this->oRole->role_id)) ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok with-text"></i>Enregistrer</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
