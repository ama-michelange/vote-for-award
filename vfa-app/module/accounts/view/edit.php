
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
								<label for="inputLogin">Login</label>
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
								<label for="inputEmail">Email</label>
								<input class="form-control" type="email" id="inputEmail" name="emailDisabled" value="<?php echo $this->oUser->email ?>" disabled />
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
							</div>
<!--							<div class="--><?php //echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?><!--">-->
<!--								<label for="inputEmail">Email</label>-->
<!--								<div class="input-group">-->
<!--									<input class="form-control" type="email" id="inputEmail" name="emailDisabled" value="--><?php //echo $this->oUser->email ?><!--" disabled />-->
<!--									<span class="input-group-btn">-->
<!--										<button class="btn btn-primary" type="button">Changer</button>-->
<!--									</span>-->
<!--								</div>-->
<!--							</div>-->

							<?php
								// Gère l'ouverture ou la fermeture du panel des mots de passe
								if ($this->oUser->openPassword ) {
									$upOrDownPassword = 'up';
									$collapsePassword = ' in';
								} else {
									$upOrDownPassword = 'down';
									$collapsePassword = '';
								}
							?>
							<div class="panel-group" id="accordion">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
												Changer mon mot de passe
											</a>
											<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
												<i data-chevron="collapse" class="glyphicon glyphicon-chevron-<?php echo $upOrDownPassword ?> with-text"></i>
											</a>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse<?php echo $collapsePassword ?>">
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
												<button class="btn btn-primary" type="submit" name="submit" value="savePassword">
													<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
												Changer mon email
											</a>
										</h4>
									</div>
									<div id="collapseTwo" class="panel-collapse collapse">
										<div class="panel-body">
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
												Collapsible Group Item #3
											</a>
										</h4>
									</div>
									<div id="collapseThree" class="panel-collapse collapse">
										<div class="panel-body">
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer clearfix">
							<div class="btn-group pull-right">
<!--								<button class="btn btn-primary" type="submit" name="submit" value="changePassword">-->
<!--									<i class="glyphicon glyphicon-ok with-text"></i>Changer-->
<!--								</button>-->
<!--								<div class="btn-group">-->
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
										Changer <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#getPassword" data-toggle="collapse">Changer mon mot de passe</a></li>
										<li><a href="#">Changer mon email</a></li>
										<li class="divider"></li>
										<li><a href="#">Sélectionner Mon login</a></li>
									</ul>
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
						<div class="panel-footer clearfix">
							<div class="pull-right">
								<button class="btn btn-primary" type="submit" name="submit" value="save">
									<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>