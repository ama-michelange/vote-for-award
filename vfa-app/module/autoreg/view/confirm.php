<?php 
/*
       * <!-- Modal --> <div id="modalReject" class="modal fade"> <div class="modal-dialog"> <div class="modal-content"> <form class="form-horizontal" action="" method="POST"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">×</button> <h3>Vous souhaitez refuser l'inscription ...</h3> </div> <div class="modal-body"> <h1>Etes-vous sûr ?</h1> </div> <div class="modal-footer"> <a class="btn btn-primary btn-sm" href="<?php echo $this->getLink('autoreg::toReject',array('id'=>_root::getParam('id'),'key'=>_root::getParam('key'))) ?>">Oui</a> <button class="btn btn-default btn-lg" data-dismiss="modal">Non</button> </div> </form> </div> </div> </div>
       */
?>
<div class="well well-sm">
	<h1 class="text-center">Bienvenue sur ALICES Award</h1>
	<h3 class="text-center">Site de vote du Prix de la Bande Dessinée</h3>
</div>
<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
<div class="alert alert-warning clearfix">
	<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
		<a class="btn btn-sm btn-warning pull-right"
			href="<?php echo $this->getLink('autoreg::index',array('id'=>$this->oConfirm->invitation_id ,'key'=>$this->oConfirm->invitation_key)) ?>">Fermer</a>
	</p>
</div>
<?php else:?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oConfirm->titleInvit ?></h3>
	</div>
	<div class="panel-body">
		<?php foreach($this->oConfirm->tInscription as $label => $value):?>
		<div class="row">
			<div class="col-sm-3 col-md-3 col-lg-2 view-label"><?php echo $label ?></div>
			<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $value ?></div>
		</div>
		<?php endforeach;?>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Validation</h3>
	</div>
	<div class="panel-body panel-condensed">
		<p>kljh azejh qsdf qspdoifu âzef razer tq^sd$f$o qsd fazpoue qusd gqpsoud gfqpsd gqs dgpoqus pogu
			qsdpof opazuef pouqsdf poauz</p>
		<div class="row">
			<div class="col-md-4">
				<form id="toIdentify" action="" method="POST">
					<input type="hidden" name="token" value="<?php echo $this->token?>" /> <input type="hidden"
						name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>" /> <input
						type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>" />
					<div class="panel panel-default panel-inner">
						<div class="panel-heading">
							<h3 class="panel-title">
								Identifiez-vous <small>pour confirmer l'inscription</small>
							</h3>
						</div>
						<div class="panel-body">
							<div
								class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_login')?>">
								<label for="cfLogin">Nom d'utilisateur</label> <input class="form-control" type="text"
									id="cfLogin" name="cf_login" placeholder="Votre nom d'utilisateur ou pseudo" /> <span
									class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_login')?></span>
							</div>
							<div
								class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'cf_password')?>">
								<label for="cfPassword">Mot de passe</label> <input class="form-control" type="password"
									id="cfPassword" name="cf_password" placeholder="Votre mot de passe" /> <span
									class="help-block"><?php echo plugin_validation::show($this->tMessage, 'cf_password')?></span>
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
				</form>
			</div>
			<div class="col-md-8">
				<form id="toRegistry" action="" method="POST">
					<input type="hidden" name="token" value="<?php echo $this->token?>" /> <input type="hidden"
						name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>" /> <input
						type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>" />
					<div class="panel panel-info panel-inner">
						<div class="panel-heading">
							<h3 class="panel-title">
								Nouveau sur ALICES Award ? <small>Enregistrez-vous</small>
							</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'login')?>">
										<label for="login">Nom d'utilisateur</label> <input class="form-control" type="text"
											id="login" name="login" value="<?php echo $this->oConfirm->login ?>"
											placeholder="Votre nom d'utilisateur ou pseudo" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?>">
										<label for="email">Email</label> <input class="form-control" type="text" id="email"
											name="email" value="<?php echo $this->oConfirm->email ?>" placeholder="Votre Email" /> <span
											class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
									</div>
								</div>
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email_bis')?>">
										<label for="email_bis">Confirmation de l'email</label> <input class="form-control"
											type="text" id="email_bis" name="email_bis"
											value="<?php echo $this->oConfirm->email_bis ?>" placeholder="Confirmez votre Email" /> <span
											class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email_bis')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'password')?>">
										<label for="password">Mot de passe</label> <input class="form-control" type="password"
											id="password" name="password" value="<?php echo $this->oConfirm->password ?>"
											placeholder="Votre mot de passe" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'password')?></span>
									</div>
								</div>
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'password_bis')?>">
										<label for="password_bis">Confirmation du mot de passe</label> <input class="form-control"
											type="password" id="password_bis" name="password_bis"
											value="<?php echo $this->oConfirm->password_bis ?>"
											placeholder="Confirmez votre mot de passe" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'password_bis')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name')?>">
										<label for="inputLastName">Nom de famille</label> <input class="form-control" type="text"
											id="inputLastName" name="last_name" value="<?php echo $this->oConfirm->last_name ?>"
											placeholder="Votre nom de famille" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name')?></span>
									</div>
								</div>
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name')?>">
										<label for="inputFirstName">Prénom</label> <input class="form-control" type="text"
											id="inputFirstName" name="first_name" value="<?php echo $this->oConfirm->first_name ?>"
											placeholder="Votre prénom" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>">
										<label for="inputBirthyear">Année de naissance</label> <select class="form-control"
											id="inputBirthyear" name="birthyear"
											data-placeholder="Sélectionner votre année de naissance">
											<option></option>
											<?php foreach($this->tSelectedYears as $year => $checked):?>
												<option value="<?php echo $year ?>" <?php if($checked): echo 'selected'; endif;?>>
													<?php echo $year?>
												</option>
											<?php endforeach;?>
										</select> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span>
									</div>
								</div>
								<?php 
/*
	       * <div class="col-sm-6"> <div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear')?>"> <label for="inputBirthyear">Année de naissance</label> <input class="form-control" type="text" id="inputBirthyear" name="birthyear" value="<?php echo $this->oConfirm->birthyear ?>" /> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear')?></span> </div> </div>
	       */
	?>
								<div class="col-sm-6">
									<div
										class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'gender')?>">
										<label>Genre</label>
										<div>
											<label class="radio-inline" for="inputHomme"> <input type="radio" id="inputHomme"
												name="gender" value="M"
												<?php if('M'==$this->oConfirm->gender): echo 'checked'; endif; ?> /> Homme
											</label> <label class="radio-inline" for="inputFemme"> <input type="radio"
												id="inputFemme" name="gender" value="F"
												<?php if('F'==$this->oConfirm->gender): echo 'checked'; endif; ?> /> Femme
											</label> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender')?></span>
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
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif;?>
