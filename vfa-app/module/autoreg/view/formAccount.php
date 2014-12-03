<form id="toRegistry" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
	<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>"/>
	<input type="hidden" name="email" value="<?php echo $this->oConfirm->email ?>"/>
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
						<label for="login">Identifiant
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="Identifiant de connexion (ou pseudo)">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<input class="form-control" type="text" id="login" name="login" autofocus="true"
								 value="<?php echo $this->oConfirm->login ?>" placeholder="Votre identifiant ou pseudo"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'login') ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'newPassword') ?>">
						<label for="newPassword">Mot de passe
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="Le mot de passe doit contenir entre 7 et 30 caractères">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<input class="form-control" type="password" id="newPassword" name="newPassword"
								 value="<?php echo $this->oConfirm->newPassword ?>" placeholder="Votre mot de passe"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'newPassword') ?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'confirmPassword') ?>">
						<label for="confirmPassword">Confirmation du mot de passe</label>
						<input class="form-control" type="password" id="confirmPassword" name="confirmPassword"
								 value="<?php echo $this->oConfirm->confirmPassword ?>" placeholder="Confirmez votre mot de passe"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'confirmPassword') ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'last_name') ?>">
						<label for="inputLastName">Nom de famille
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="Le nom et le prénom servent au correspondant du prix pour vous identifier lors des prêts d'albums">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<input class="form-control" type="text" id="inputLastName" name="last_name"
								 value="<?php echo $this->oConfirm->last_name ?>" placeholder="Votre nom de famille"/>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'last_name') ?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'first_name') ?>">
						<label for="inputFirstName">Prénom
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="Le nom et le prénom servent au correspondant du prix pour vous identifier lors des prêts d'albums">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
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
						<label for="inputBirthyear">Année de naissance
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="L'année de naissance et le genre alimentent les statistiques d'aide au choix des albums">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
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
						<label>Genre
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="L'année de naissance et le genre alimentent les statistiques d'aide au choix des albums">
									<i class="glyphicon glyphicon-info-sign"></i>
							</span>

						</label>

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
				<button type="submit" class="btn btn-info" name="action" value="toRegistry">
					<i class="glyphicon glyphicon-ok with-text"></i>S'enregistrer
				</button>
				<a id="cancelAccount" class="btn btn-default"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			</div>
		</div>
	</div>
	<a name="newValidation"></a>
</form>
