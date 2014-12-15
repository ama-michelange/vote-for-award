<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden"	name="group_id" value="<?php echo $this->oGroup->group_id ?>" />
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->textTitle ?></h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right"
						href="<?php echo $this->getLink('groups::index') ?>">Fermer</a>
				</p>
			</div>
		</div>
		<?php else:?>
		<div class="panel-body">
			<div
				class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'group_name')?>">
				<label for="inputName">
					Nom
					<span class="btn btn-xs btn-link" data-rel="tooltip"
						data-original-title="Nom du groupe, du comité d'entreprise, de l'organisation, ...">
						<i class="glyphicon glyphicon-info-sign"></i>
					</span>
				</label>
				<input class="form-control" type="text" id="inputName" name="group_name" value="<?php echo $this->oGroup->group_name ?>" />
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'group_name')?></span>
			</div>
			<?php	if ($this->__isset('tSelectedAwards')): ?>
				<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'award_ids')?>">
					<label for="inputAwards">Inscription au prix
						<span class="btn btn-xs btn-link" data-rel="tooltip"
							data-original-title="Choisissez le prix du groupe auxquels participeront les utilisateurs une fois inscrits">
							<i class="glyphicon glyphicon-info-sign"></i>
						</span>
					</label>
					<select id="inputAwards" class="form-control" name="award_ids[]" size="13" multiple>
						<?php foreach($this->tSelectedAwards as $tAward):?>
							<option value="<?php echo $tAward[0] ?>" <?php if($tAward[2]): echo 'selected'; endif;?>>
								<?php echo $tAward[1]?>
							</option>
						<?php endforeach;?>
					</select>
					<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'award_ids')?></span>
				</div>
			<?php endif;?>
			<?php	if ($this->__isset('tSelectedRoles')): ?>
				<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'role_id_default')?>">
					<label for="inputRoleDefault">Rôle minimal
						<span class="btn btn-xs btn-link" data-rel="tooltip"
							data-original-title="Choisissez le rôle minimal des utilisateurs à associer au groupe.">
							<i class="glyphicon glyphicon-info-sign"></i>
						</span>
					</label>
					<select id="inputRoleDefault" class="form-control" name="role_id_default" size="13">
						<?php foreach($this->tSelectedRoles as $tRole):?>
							<option value="<?php echo $tRole[0] ?>" <?php if($tRole[2]): echo 'selected'; endif;?>>
								<?php echo $tRole[1]?>
							</option>
						<?php endforeach;?>
					</select>
					<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'role_id_default')?></span>
				</div>
			<?php else:?>
				<input type="hidden"	name="role_id_default" value="<?php echo $this->oGroup->role_id_default ?>" />
			<?php endif;?>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<?php if(trim($this->oGroup->group_id)==false):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('groups::index') ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
					<a class="btn btn-default"
					href="<?php echo $this->getLink('groups::read',array('id'=>$this->oGroup->group_id)) ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
				<button class="btn btn-primary" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
				</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
