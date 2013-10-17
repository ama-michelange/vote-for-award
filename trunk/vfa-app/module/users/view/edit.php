<div class="alert alert-info">
	<div class="row">
		<h3><?php echo $this->textTitle ?></h3>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="alert alert-danger">
			<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
			<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('users::index') ?>">Fermer</a></p>
		</div>		
		<?php else:?>
		<div class="col-md-12">
		<form class="form-horizontal" action="" method="POST" >
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<input type="hidden" name="user_id" value="<?php echo $this->oUser->user_id ?>" />
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'username')?>">
				<label class="control-label" for="inputUsername">Pseudo</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="inputUsername" name="username" value="<?php echo $this->oUser->username ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'username')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'email')?>">
				<label class="control-label" for="inputEmail">Email</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="inputEmail" name="email" value="<?php echo $this->oUser->email ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'last_name')?>">
				<label class="control-label" for="inputLastName">Nom</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="inputLastName" name="last_name" value="<?php echo $this->oUser->last_name ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'last_name')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'first_name')?>">
				<label class="control-label" for="inputFirstName">Prénom</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="inputFirstName" name="first_name" value="<?php echo $this->oUser->first_name ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'first_name')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'birthyear')?>">
				<label class="control-label" for="inputBirthyear">Année de naissance</label>
				<div class="controls">
					<input class="input-mini" type="text" id="inputBirthyear" name="birthyear" value="<?php echo $this->oUser->birthyear ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'gender')?>">
				<label class="control-label">Genre</label>
				<div class="controls">
					<label class="radio-inline" for="inputHomme">
						<input type="radio" id="inputHomme" name="gender" value="M" <?php if('M'==$this->oUser->gender): echo 'checked'; endif; ?> />
						Homme
					</label>
					<label class="radio-inline" for="inputFemme">
						<input type="radio" id="inputFemme" name="gender" value="F" <?php if('F'==$this->oUser->gender): echo 'checked'; endif; ?> />
						Femme
					</label>
					<span class="radio-inline help-inline"><?php echo plugin_validation::show($this->tMessage, 'gender')?></span>
				</div>
			</div>
			
			<?php /* Je ne sais plus à quoi peut servir ce champ !?!
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'vote')?>">
				<label class="control-label" for="inputVote">Electeur</label>
				<div class="controls">
					<input type="checkbox" id="inputVote" name="vote" <?php if ($this->oUser->vote): echo 'checked'; endif; ?> />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'vote')?></span>
				</div>
			</div>
			*/ ?>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'user_groups')?>">
				<label class="control-label" for="inputUserGroups">Groupes 
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Groupes" 
						data-content="Choisir un ou plusieurs groupes pour l'utilisateur.">
						<i class="glyphicon glyphicon-info-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls" >
					<select id="inputUserGroups" name="user_groups[]"  size="13" multiple>
						<?php foreach($this->tSelectedGroups as $tGroup):?>
						<option value="<?php echo $tGroup[0] ?>" <?php if($tGroup[2]): echo 'selected'; endif;?>>
							<?php echo $tGroup[1] ?>
						</option>
						<?php endforeach;?>
					</select>
					<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'user_groups')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'user_roles')?>">
				<label class="control-label" for="inputUserRoles">Rôles 
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Rôles" 
						data-content="Choisir un ou plusieurs rôles pour l'utilisateur.">
						<i class="glyphicon glyphicon-info-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<select id="inputUserRoles" name="user_roles[]" class="input-xxlarge" size="13" multiple>
						<?php foreach($this->tSelectedRoles as $tRole):?>
						<option value="<?php echo $tRole[0] ?>" <?php if($tRole[2]): echo 'selected'; endif;?>>
							<?php echo $tRole[1] ?>
						</option>
						<?php endforeach;?>
					</select>
					<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'user_roles')?></span>
				</div>
			</div>
			
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Enregistrer</button>
					<a class="btn btn-default" href="<?php echo $this->getLink('users::read',array('id'=>$this->oUser->user_id)) ?>"><i class="glyphicon glyphicon-remove"></i> Annuler</a>
				</div>
			</div>
		</form>
		</div>
		<?php endif;?>
	</div>
</div>
		