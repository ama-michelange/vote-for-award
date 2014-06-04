<form id="toRegistry" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
	<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>"/>
	<input type="text" name="hiddenLogin" value="" style="display: none"/>
	<input type="password" name="hiddenPassword" value="" style="display: none"/>

	<div class="panel panel-default panel-inner">
		<div class="panel-heading">
			<h3 class="panel-title">
			Enregistrez-vous
				<small>sur <?php echo _root::getConfigVar('vfa-app.title') ?> pour confirmer votre inscription</small>
			</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'login') ?>">
						<label for="login">Identifiant</label>
						<input class="form-control" type="text" id="login" name="login" autofocus="true"
								 value="<?php echo $this->oConfirm->login ?>" placeholder="Votre identifiant ou pseudo"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login') ?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email') ?>">
						<label for="email">Email</label>
						<input type="hidden" name="email" value="<?php echo $this->oConfirm->email ?>"/>
						<input class="form-control" type="text" id="email" name="email" disabled="disabled"
								 value="<?php echo $this->oConfirm->email ?>" placeholder="Votre Email"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email') ?></span>
					</div>
				</div>
				<!--				<div class="col-sm-6">-->
				<!--					<div class="-->
				<?php //echo plugin_validation::addClassError('form-group', $this->tMessage, 'email_bis') ?><!--">-->
				<!--						<label for="email_bis">Confirmation de l'email</label>-->
				<!--						<input class="form-control" type="text" id="email_bis" name="email_bis"-->
				<!--								 value="--><?php //echo $this->oConfirm->email_bis ?><!--" placeholder="Confirmez votre Email"/>-->
				<!--						<span class="help-block">-->
				<?php //echo plugin_validation::show($this->tMessage, 'email_bis') ?><!--</span>-->
				<!--					</div>-->
				<!--				</div>-->
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'password') ?>">
						<label for="password">Mot de passe</label>
						<input class="form-control" type="password" id="password" name="password"
								 value="<?php echo $this->oConfirm->password ?>" placeholder="Votre mot de passe"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'password') ?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'password_bis') ?>">
						<label for="password_bis">Confirmation du mot de passe</label>
						<input class="form-control" type="password" id="password_bis" name="password_bis"
								 value="<?php echo $this->oConfirm->password_bis ?>" placeholder="Confirmez votre mot de passe"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'password_bis') ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name') ?>">
						<label for="inputLastName">Nom de famille</label>
						<input class="form-control" type="text" id="inputLastName" name="last_name"
								 value="<?php echo $this->oConfirm->last_name ?>" placeholder="Votre nom de famille"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name') ?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name') ?>">
						<label for="inputFirstName">Prénom</label>
						<input class="form-control" type="text" id="inputFirstName" name="first_name"
								 value="<?php echo $this->oConfirm->first_name ?>" placeholder="Votre prénom"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'first_name') ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'birthyear') ?>">
						<label for="inputBirthyear">Année de naissance</label>
						<select class="form-control" id="inputBirthyear" name="birthyear"
								  data-placeholder="Sélectionner votre année de naissance">
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
									<?php if ('M' == $this->oConfirm->gender): echo 'checked'; endif; ?> />
								Homme
							</label>
							<label class="radio-inline" for="inputFemme">
								<input type="radio" id="inputFemme" name="gender" value="F"
									<?php if ('F' == $this->oConfirm->gender): echo 'checked'; endif; ?> />
								Femme
							</label>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'gender') ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<a id="cancelAccount" class="btn btn-default"><i class="glyphicon glyphicon-ok with-text"></i>Annuler</a>
				<button type="submit" class="btn btn-info" name="action" value="toRegistry">
					<i class="glyphicon glyphicon-ok with-text"></i>S'enregistrer
				</button>
			</div>
		</div>
	</div>
	<a name="newValidation"></a>
</form>
