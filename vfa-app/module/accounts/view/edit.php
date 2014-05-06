
<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>" />
	<input type="hidden" name="user_id" value="<?php echo $this->oUser->user_id ?>" />
	<input type="hidden" name="login" value="<?php echo $this->oUser->login ?>" />
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
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'login')?>">
								<label for="inputLogin">Identifiant</label>
								<input class="form-control" type="text" id="inputLogin" name="loginDisabled" value="<?php echo $this->oUser->login ?>" disabled />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login')?></span>
							</div>
<!--							<div>-->
<!--								<a class="btn btn-info btn-block" href="#changeEmail">-->
<!--									Changer mon email-->
<!--									<i class="glyphicon glyphicon-mail with-text"></i>-->
<!--								</a>-->
<!--							</div>-->
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?>">
								<label for="inputEmail">Adresse Email</label>
								<input class="form-control" type="email" id="inputEmail" name="emailDisabled" value="<?php echo $this->oUser->email ?>" disabled />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
							</div>

							<?php
								// Gère l'ouverture ou la fermeture du panel des mots de passe
								if ($this->oUser->openPassword ) {
									$upOrDownPassword = 'up';
									$collapseInPassword = ' in';
								} else {
									$upOrDownPassword = 'down';
									$collapseInPassword = '';
								}
								// Gère l'ouverture ou la fermeture du panel de sélection du login
								if ($this->oUser->openLogin ) {
									$upOrDownLogin = 'up';
									$collapseInLogin = ' in';
								} else {
									$upOrDownLogin = 'down';
									$collapseInLogin = '';
								}
								// Gère l'ouverture ou la fermeture du panel de sélection de l'email
								if ($this->oUser->openEmail ) {
									$upOrDownEmail = 'up';
									$collapseInEmail = ' in';
								} else {
									$upOrDownEmail = 'down';
									$collapseInEmail = '';
								}
							?>
							<div class="panel-group" id="accordion">
								<?php if ($this->changeLogin): ?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseLogin">
												Changer mon identifiant
											</a>
											<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseLogin">
												<i data-chevron="collapse" class="glyphicon glyphicon-collapse-<?php echo $upOrDownLogin ?> text-primary"></i>
											</a>
										</h4>
									</div>
									<div id="collapseLogin" class="panel-collapse collapse<?php echo $collapseInLogin ?>">
										<div class="panel-body">
											<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newLogin')?>">
												<select id="inputNewLogin" class="form-control" name="newLogin" size="2" data-placeholder="Sélectionnez votre identifiant">
													<option></option>
													<optgroup label="Email">
														<option value="<?php echo $this->oUser->email ?>">
															<?php echo $this->oUser->email ?>
														</option>
													</optgroup>
													<optgroup label="Alias">
														<option value="<?php echo $this->oUser->alias ?>">
															<?php echo $this->oUser->alias ?>
														</option>
													</optgroup>
												</select>
												<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newLogin')?></span>
											</div>
										</div>
										<div class="panel-footer clearfix">
											<div class="pull-right">
												<button class="btn btn-primary btn-sm" type="submit" name="submit" value="saveLogin">
													<i class="glyphicon glyphicon-ok with-text"></i>Changer
												</button>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapsePassword">
												Changer mon mot de passe
											</a>
											<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapsePassword">
												<i data-chevron="collapse" class="glyphicon glyphicon-collapse-<?php echo $upOrDownPassword ?> text-primary"></i>
											</a>
										</h4>
									</div>
									<div id="collapsePassword" class="panel-collapse collapse<?php echo $collapseInPassword ?>">
										<div class="panel-body">
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
										<div class="panel-footer clearfix">
											<div class="pull-right">
												<button class="btn btn-primary btn-sm" type="submit" name="submit" value="savePassword">
													<i class="glyphicon glyphicon-ok with-text"></i>Changer
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseEmail">
												Changer mon adresse Email
											</a>
											<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseEmail">
												<i data-chevron="collapse" class="glyphicon glyphicon-collapse-<?php echo $upOrDownEmail ?> text-primary"></i>
											</a>
										</h4>
									</div>
									<div id="collapseEmail" class="panel-collapse collapse<?php echo $collapseInEmail ?>">
										<div class="panel-body">
											<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newEmail')?>">
												<label for="inputNewEmail">Nouvelle adresse Email</label>
												<input class="form-control" type="email" id="inputNewEmail" name="newEmail" value="<?php echo $this->oUser->newEmail ?>" />
												<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newEmail')?></span>
											</div>
										</div>
										<div class="panel-footer clearfix">
											<div class="pull-right">
												<button class="btn btn-primary btn-sm" type="submit" name="submit" value="saveEmail">
													<i class="glyphicon glyphicon-ok with-text"></i>Changer
												</button>
											</div>
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
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'alias')?>">
								<label for="inputAlias">Alias</label>
								<input class="form-control" type="text" id="inputAlias" name="alias" value="<?php echo $this->oUser->alias ?>"/>
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'alias')?></span>
							</div>
							<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name')?>">
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
								<div class="col-sm-6 col-md-4">
									<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>">
										<label for="inputBirthyear">Année de naissance</label>
										<select class="form-control"
												  id="inputBirthyear" name="birthyear"
												  data-placeholder="Sélectionnez">
											<option></option>
											<?php foreach($this->tSelectedYears as $year => $checked):?>
												<option value="<?php echo $year ?>" <?php if($checked): echo 'selected'; endif;?>>
													<?php echo $year?>
												</option>
											<?php endforeach;?>
										</select>
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
						<div class="panel-footer clearfix">
							<div class="pull-right">
								<button class="btn btn-primary btn-sm" type="submit" name="submit" value="save">
									<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
		<?php
			$oUserSession = _root::getAuth()->getUserSession();
			var_dump($oUserSession->getValidAwards());
			var_dump($oUserSession->getReaderGroup());
			var_dump($oUserSession->getReaderGroup());
		?>
	</div>
</form>