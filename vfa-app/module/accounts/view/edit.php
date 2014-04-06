
<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="user_id" value="<?php echo $this->oUser->user_id ?>" />
	<input type="hidden" name="email" value="<?php echo $this->oUser->email ?>" />
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Mon compte</h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right"
						href="<?php echo $this->getLink('home::index') ?>">Fermer</a>
				</p>
			</div>
		</div>
		<?php else:?>
		<div class="panel-body panel-condensed">
			<div class="row">
				<div class="col-sm-6 col-md-6">
					<div class="panel panel-info panel-inner">
<!--						<div class="panel-heading">-->
<!--							<h3 class="panel-title">Paramètres d'identification</h3>-->
<!--						</div>-->
						<div class="panel-body">
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?>">
								<label for="inputEmail">Email</label>
								<input class="form-control" type="email" id="inputEmail" name="emailDisabled" value="<?php echo $this->oUser->email ?>" disabled />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
							</div>
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'username')?>">
								<label for="inputUsername">Alias ou Pseudo</label>
								<input class="form-control" type="text" id="inputUsername" name="username" value="<?php echo $this->oUser->username ?>"/>
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'username')?></span>
							</div>
							<?php
								// Gère l'ouverture ou la fermeture du panel des mots de passe
								if ($this->oUser->openPassword ) {
									$upOrDown = 'up';
									$collapseIn = ' in';
								} else {
									$upOrDown = 'down';
									$collapseIn = '';
								}
							?>
							<div>
								<a class="btn btn-info btn-block" data-toggle="collapse" href="#getPassword">
									Changer mon mot de passe
									<i data-chevron="collapse" class="pull-right glyphicon glyphicon-chevron-<?php echo $upOrDown ?> with-text"></i>
								</a>
							</div>
							<div id="getPassword" class="collapse<?php echo $collapseIn ?>">
								<div class="panel panel-default panel-inner">
									<div class="panel-body panel-condensed">
										<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newPassword')?>">
											<label for="inputNewPass">Nouveau mot de passe</label>
											<input class="form-control" type="password" id="inputNewPass" name="newPassword" value="<?php echo $this->oUser->newPassword ?>" autocomplete="off"/>
											<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newPassword')?></span>
										</div>
										<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmPassword')?>">
											<label for="inputNewPassConfirm">Confirmation du mot de passe</label>
											<input class="form-control" type="password" id="inputNewPassConfirm" name="confirmPassword" value="<?php echo $this->oUser->confirmPassword ?>" autocomplete="off" />
											<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmPassword')?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="panel panel-info panel-inner">
<!--						<div class="panel-heading">-->
<!--							<h3 class="panel-title">Infos personnelles</h3>-->
<!--						</div>-->
						<div class="panel-body">
							<div
								class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name')?>">
								<label for="inputLastName">Nom</label>
								<input class="form-control" type="text" id="inputLastName" name="last_name" value="<?php echo $this->oUser->last_name ?>" />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name')?></span>
							</div>
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name')?>">
								<label for="inputFirstName">Prénom</label>
								<input class="form-control" type="text" id="inputFirstName" name="first_name" value="<?php echo $this->oUser->first_name ?>" />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name')?></span>
							</div>
							<div class="row">
								<div class="col-sm-6 col-md-6">
									<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>">
										<label for="inputBirthyear">Année de naissance</label>
										<input class="form-control" type="text" id="inputBirthyear" name="birthyear" value="<?php echo $this->oUser->birthyear ?>" />
										<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'gender')?>">
										<label>Genre</label>
										<div>
											<label class="radio-inline" for="inputHomme">
												<input type="radio" id="inputHomme" name="gender" value="M" <?php if('M'==$this->oUser->gender): echo 'checked'; endif; ?> />
												Homme
											</label>
											<label class="radio-inline" for="inputFemme">
												<input type="radio" id="inputFemme" name="gender" value="F" <?php if('F'==$this->oUser->gender): echo 'checked'; endif; ?> />
												Femme
											</label>
											<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender')?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button class="btn btn-primary" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
				</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
