<div class="well well-sm">
	<h1 class="text-center">Bienvenue sur ALICES Award</h1>
	<h3 class="text-center">Site de vote du Prix de la Bande Dessinée</h3>
</div>
<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
<div class="alert alert-warning clearfix">
	<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
		<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink(_root::getParamNav(),array('id'=>_root::getParam('id'),'key'=>_root::getParam('key'))) ?>">Fermer</a></p>
</div>		
<?php else:?>
<div class="panel panel-default">
	<div class="panel-body">
		<h3 class="panel-title"><?php echo $this->oConfirm->textInvit ?></h3>
	</div>
</div>
<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>" />
	<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>" /> 
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Identifiez-vous <small>pour confirmer l'inscription</small></h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label for="cfLogin">Nom d'utilisateur ou email</label>
						<input class="form-control" type="text" id="cfLogin" name="cf-login" placeholder="Votre nom d'utilisateur ou email" />
					</div>
					<div class="form-group">
						<label for="cfPassword">Mot de passe</label>
						<input class="form-control" type="password" id="cfPassword" name="cf-password" placeholder="Votre mot de passe" />
					</div>
				</div>
				<div class="panel-footer clearfix">
					<div class="pull-right">
						<button type="submit" class="btn btn-default" name="action" value="toIdentify">
							<i class="glyphicon glyphicon-ok with-text"></i>S'identifier
						</button>
					</div>
					<a href="#">Mot de passe oublié ?</a>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Nouveau sur ALICES Award ? <small>Enregistrez-vous</small></h3>
				</div>
					<div class="panel-body">
					<div class="form-group">
						<label for="ident">Nom d'utilisateur</label>
						<input class="form-control" type="text" id="ident" name="ident" placeholder="Votre nom d'utilisateur" />
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" id="email" name="email" placeholder="Votre Email" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email-bis">Confirmation de l'email</label>
								<input class="form-control" type="text" id="email-bis" name="email-bis" placeholder="Confirmez votre Email" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="password">Mot de passe</label> 
								<input class="form-control" type="password" id="password" name="password" placeholder="Votre mot de passe" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="password-bis">Confirmation du mot de passe</label> 
								<input class="form-control" type="password" id="password-bis" name="password-bis" placeholder="Confirmez votre mot de passe" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name')?>">
								<label for="inputLastName">Nom</label>
								<input class="form-control" type="text" id="inputLastName" name="last_name" value="<?php /* echo $this->oUser->last_name */?>" />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name')?></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name')?>">
								<label for="inputFirstName">Prénom</label>
								<input class="form-control" type="text" id="inputFirstName" name="first_name" value="<?php  /* echo $this->oUser->first_name */?>" />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name')?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>">
								<label for="inputBirthyear">Année de naissance</label>
								<input class="form-control" type="text" id="inputBirthyear" name="birthyear" value="<?php /* echo $this->oUser->birthyear */ ?>" />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'gender')?>">
								<label>Genre</label>
								<div>
									<label class="radio-inline" for="inputHomme">
										<input type="radio" id="inputHomme" name="gender" value="M" <?php /* if('M'==$this->oUser->gender): echo 'checked'; endif; */?> />
										Homme
									</label>
									<label class="radio-inline" for="inputFemme">
										<input type="radio" id="inputFemme" name="gender" value="F" <?php /* if('F'==$this->oUser->gender): echo 'checked'; endif; */?> />
										Femme
									</label>
									<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender')?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer clearfix">
					<div class="pull-right">
						<button type="submit" class="btn btn-info" name="action" value="toRegistry">
							<i class="glyphicon glyphicon-ok with-text"></i>S'enregistrer
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php endif;?>
