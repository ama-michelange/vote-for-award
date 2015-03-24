<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="user_id" value="<?php echo $this->oUser->user_id ?>" />
	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->textTitle ?></h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('users::index') ?>">Fermer</a>
				</p>
			</div>
		</div>		
		<?php else:?>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'login')?>">
						<label for="inputUsername">Identifiant</label> <input class="form-control" type="text"
							id="inputUsername" name="login" value="<?php echo $this->oUser->login ?>" /> <span
							class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login')?></span>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?>">
						<label for="inputEmail">Email</label> <input class="form-control" type="text" id="inputEmail"
							name="email" value="<?php echo $this->oUser->email ?>" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name')?>">
						<label for="inputLastName">Nom</label> <input class="form-control" type="text"
							id="inputLastName" name="last_name" value="<?php echo $this->oUser->last_name ?>" /> <span
							class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name')?></span>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name')?>">
						<label for="inputFirstName">Prénom</label> <input class="form-control" type="text"
							id="inputFirstName" name="first_name" value="<?php echo $this->oUser->first_name ?>" /> <span
							class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name')?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>">
						<label for="inputBirthyear">Année de naissance</label>
						<input class="form-control" type="text" id="inputBirthyear" name="birthyear" value="<?php echo $this->oUser->birthyear ?>" autocomplete="off" />
						<span	class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'gender')?>">
						<label>Genre</label>
						<div>
							<label class="radio-inline" for="inputHomme"> <input type="radio" id="inputHomme"
								name="gender" value="M" <?php if('M'==$this->oUser->gender): echo 'checked'; endif; ?> />
								Homme
							</label>
							<label class="radio-inline" for="inputFemme"> <input type="radio" id="inputFemme"
								name="gender" value="F" <?php if('F'==$this->oUser->gender): echo 'checked'; endif; ?> />
								Femme
							</label>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender')?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newPassword')?>">
						<label for="inputNewPass">Nouveau mot de passe</label>
						<input class="form-control" type="password" id="inputNewPass" name="newPassword" value="<?php echo $this->oUser->newPassword ?>" autocomplete="off" />
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newPassword')?></span>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmPassword')?>">
						<label for="inputNewPassConfirm">Confirmation du mot de passe</label>
						<input class="form-control" type="password" id="inputNewPassConfirm" name="confirmPassword" value="<?php echo $this->oUser->confirmPassword ?>" autocomplete="off" />
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmPassword')?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'user_groups')?>">
						<label for="inputUserGroups">Groupes</label>
						<select id="inputUserGroups" class="form-control" name="user_groups[]" size="13"	multiple>
							<?php foreach($this->tSelectedGroups as $tGroup):?>
								<option value="<?php echo $tGroup[0] ?>" <?php if($tGroup[2]): echo 'selected'; endif;?>>
									<?php echo $tGroup[1]?>
								</option>
							<?php endforeach;?>
						</select>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'user_groups')?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'user_roles')?>">
						<label for="inputUserRoles">Rôles</label>
						<select id="inputUserRoles" class="form-control" name="user_roles[]"	size="13" multiple>
							<?php foreach($this->tSelectedRoles as $tRole):?>
								<option value="<?php echo $tRole[0] ?>" <?php if($tRole[2]): echo 'selected'; endif;?>>
									<?php echo $tRole[1]?>
								</option>
							<?php endforeach;?>
						</select>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'user_roles')?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'user_awards')?>">
						<label for="inputUserAwards">Prix</label>
						<select id="inputUserAwards" class="form-control" name="user_awards[]" size="13"	multiple>
							<?php foreach($this->tSelectedAwards as $tAward):?>
								<option value="<?php echo $tAward[0] ?>" <?php if($tAward[2]): echo 'selected'; endif;?>>
									<?php echo $tAward[1]?>
								</option>
							<?php endforeach;?>
						</select>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'user_awards')?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button class="btn btn-primary" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
				</button>
				<?php if(trim($this->oUser->user_id)==false):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('users::list') ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
					<a class="btn btn-default"
					href="<?php echo $this->getLink('users::read',array('id'=>$this->oUser->user_id)) ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
