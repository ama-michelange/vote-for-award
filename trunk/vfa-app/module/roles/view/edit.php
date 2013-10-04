<div class="alert alert-info">
	<div class="row">
		<h3><?php echo $this->textTitle ?></h3>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="alert alert-error">
			<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
			<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('roles::index') ?>">Fermer</a></p>
		</div>		
		<?php else:?>
		<form class="form-horizontal" action="" method="POST" >
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<input type="hidden" name="role_id" value="<?php echo $this->oRole->role_id ?>" />
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'role_name')?>">
				<label class="control-label" for="inputName">Nom
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Nom" 
						data-content="Le nom du rôle">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<input type="text" id="inputName" name="role_name" value="<?php echo $this->oRole->role_name ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'role_name')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'description')?>">
				<label class="control-label" for="inputDescription">Description
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Description" 
						data-content="La description du rôle">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<textarea class="col-md-12" id="inputDescription" name="description"><?php echo $this->oRole->description ?></textarea>
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'description')?></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">
					<a class="accordion-toggle" data-toggle="collapse" href="#acl">Habilitations</a>
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Habilitations" 
						data-content="Les habilitations du rôle. Cliquez sur le libellé pour fermer ou ouvrir le tableau des habilitations.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
					<a class="accordion-toggle" data-toggle="collapse" href="#acl"><i class="glyphicon glyphicon-chevron-right glyphicon glyphicon-white"></i></a>
				</label>
				<div id="acl" class="controls collapse in">			
					<?php if($this->tCompleteAclModules):?>
					<table class="table table-hover table-condensed" style="background-color: white;">
						<thead>
							<tr>
								<th class="col-md-2">Modules</th>
								<th>Actions</th>
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
											<strong><?php echo $checkbox['action']?></strong>
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
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i>&nbsp;&nbsp;Enregistrer</button>
					<?php if(trim($this->oRole->role_id)==false):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('roles::index') ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
					<?php else:?>
					<a class="btn btn-default" href="<?php echo $this->getLink('roles::read',array('id'=>$this->oRole->role_id)) ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
					<?php endif;?>
				</div>
			</div>
		</form>
		<?php endif;?>
	</div>
</div>
