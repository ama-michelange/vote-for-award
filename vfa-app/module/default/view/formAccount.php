<form id="toRegistry" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="regin_id" value="<?php echo $this->oRegistry->regin_id ?>"/>
	<input type="text" name="hiddenLogin" value="" style="display: none"/>
	<input type="password" name="hiddenPassword" value="" style="display: none"/>

	<div class="panel panel-default panel-inner">
		<div class="panel-heading">
			<h3 class="panel-title">Création de votre compte</h3>
		</div>
		<div class="panel-body">
			<fieldset>
				<legend>Informations de contact</legend>
				<div class="row">
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name') ?>">
							<label for="inputLastName">Nom</label>
							<input class="form-control" type="text" id="inputLastName" name="last_name"
										 value="<?php echo $this->oRegistry->last_name ?>" placeholder="Votre nom de famille"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name') ?>">
							<label for="inputFirstName">Prénom</label>
							<input class="form-control" type="text" id="inputFirstName" name="first_name"
										 value="<?php echo $this->oRegistry->first_name ?>" placeholder="Votre prénom"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name') ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email') ?>">
							<label for="inputEmail">Email</label>
							<input class="form-control" type="text" id="inputEmail" name="email"
										 value="<?php echo $this->oRegistry->email ?>" placeholder="Votre email"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmEmail') ?>">
							<label for="inputConfirmEmail">Confirmation de l'email</label>
							<input class="form-control" type="text" id="inputConfirmEmail" name="confirmEmail"
										 value="<?php echo $this->oRegistry->confirmEmail ?>" placeholder="Confirmez votre email"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmEmail') ?></span>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>Informations statistiques</legend>
				<div class="row">
					<div class="col-sm-6">
						<div
							class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear') ?>">
							<label for="inputBirthyear">Année de naissance </label>
							<select class="form-control" id="inputBirthyear" name="birthyear"
											data-placeholder="Sélectionnez votre année de naissance">
								<option></option>
								<?php foreach ($this->tSelectedYears as $year => $checked): ?>
									<option value="<?php echo $year ?>" <?php if ($checked): echo 'selected'; endif; ?>>
										<?php echo $year ?>
									</option>
								<?php endforeach; ?>
							</select>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'birthyear') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'gender') ?>">
							<label>Genre</label>

							<div>
								<label class="radio-inline" for="inputHomme">
									<input type="radio" id="inputHomme" name="gender" value="M"
										<?php if ('M' == $this->oRegistry->gender): echo 'checked'; endif; ?> />
									Homme
								</label>
								<label class="radio-inline" for="inputFemme">
									<input type="radio" id="inputFemme" name="gender" value="F"
										<?php if ('F' == $this->oRegistry->gender): echo 'checked'; endif; ?> />
									Femme
								</label>
								<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender') ?></span>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>Informations de connexion</legend>
				<div class="row">
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'login') ?>">
							<label for="login">Identifiant</label>
							<input class="form-control" type="text" id="login" name="login"
										 value="<?php echo $this->oRegistry->login ?>" placeholder="Votre identifiant ou pseudo"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login') ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newPassword') ?>">
							<label for="newPassword">Mot de passe
							<span class="btn btn-xs btn-link" data-rel="tooltip"
										data-original-title="Le mot de passe doit contenir entre 7 et 50 caractères">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
							</label>
							<input class="form-control" type="password" id="newPassword" name="newPassword"
										 value="<?php echo $this->oRegistry->newPassword ?>" placeholder="Votre mot de passe"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newPassword') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div
							class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmPassword') ?>">
							<label for="confirmPassword">Confirmation du mot de passe</label>
							<input class="form-control" type="password" id="confirmPassword" name="confirmPassword"
										 value="<?php echo $this->oRegistry->confirmPassword ?>"
										 placeholder="Confirmez votre mot de passe"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmPassword') ?></span>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button id="submitAccount" type="submit" class="btn btn-info" name="action" value="toAccount">
					<i class="glyphicon glyphicon-ok with-text"></i>S'enregistrer
				</button>
				<a id="cancelAccount" class="btn btn-default"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			</div>
		</div>
	</div>
	<a name="newValidation"></a>
</form>
