<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="type" value="<?php echo $this->oRegin->type ?>"/>
	<input type="hidden" name="code" value="<?php echo $this->oRegin->code ?>"/>
	<input type="hidden" name="state" value="<?php echo $this->oRegin->state ?>"/>
	<input type="hidden" name="created_user_id" value="<?php echo $this->oRegin->created_user_id ?>"/>
	<!--	<input type="hidden" name="awards_ids" value="--><?php //echo $this->oRegin->awards_ids ?><!--"/>-->
	<!--	<input type="hidden" name="group_id" value="--><?php //echo $this->oRegin->group_id ?><!--"/>-->

	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Permission pour un correspondant</h3>
		</div>
		<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
			<div class="panel-body">
				<div class="alert alert-warning clearfix">
					<p><?php echo plugin_validation::show($this->tMessage, 'token') ?>
						<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a>
					</p>
				</div>
			</div>
		<?php else: ?>
		<div class="panel-body panel-condensed">
			<div class="panel panel-info">
				<div class="panel-body panel-condensed">
					<p>Cette permission permet à la personne qui l'utilise :
					<ul>
						<li>De devenir <strong>Correspondant</strong>, c'est à dire de gérer le prix et de créer la permission
							d'inscription des lecteurs de son groupe pour participer aux votes
						</li>
						<li>De s'inscrire au prix pour participer aux votes</li>
						<li>Et le cas échéant d'ouvrir un compte sur ce site</li>
					</ul>
					</p>

					<p>Lors de cette inscription, le correspondant a besoin d'un code d'inscription que vous allez lui
						founir. Il sert à donner les droits de correspondant, de l'associer à son groupe et au prix en cours
						lorsqu'il ouvre un compte ou s'identifie sur le site. Ce code est à usage unique pour un correspondant du
						groupe (CE, association, ...)</p>

					<p>Vous obtiendrez le code d'inscription dans l'écran suivant qu'il faudra transmettre à la personne
						adéquate.</p>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Caractéristique de l'inscription du correspondant</h3>
				</div>
				<div class="panel-body panel-condensed">
					<div class="row">
						<div class="col-sm-12">
							<div
								class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'group_id') ?>">
								<label for="inputGroup">Affectation au groupe</label>

								<div class="row">
									<div class="col-sm-6">
										<select id="inputGroup" class="form-control" name="group_id" size="13">
											<?php foreach ($this->tSelectedGroups as $tGroup): ?>
												<option value="<?php echo $tGroup[0] ?>" <?php if ($tGroup[2]): echo 'selected'; endif; ?>>
													<?php echo $tGroup[1] ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-6">
										Sélectionnez un groupe ou bien créez en un nouveau via le bouton "Groupes" du menu
									</div>
								</div>
										<span
											class="help-block"><?php echo plugin_validation::show($this->tMessage, 'group_id') ?></span>
							</div>
						</div>
						<!--						<div class="row">-->
						<!--							<div class="col-sm-4">-->
						<!--								<div class="form-group">-->
						<!--									<label for="inputPrix">Inscription au prix</label>-->
						<!--									<input class="form-control" type="text" id="inputPrix" name="_prix"-->
						<!--												 value="--><?php //echo $this->tAwards[0]->toString() ?><!--" disabled/>-->
						<!--								</div>-->
						<!--							</div>-->
						<!--							--><?php //for ($i = 1; $i < count($this->tAwards); $i++) : ?>
						<!--								<div class="col-sm-4">-->
						<!--									<div class="form-group">-->
						<!--										<label for="inputPrix">Inscription au prix</label>-->
						<!--										<input class="form-control" type="text" id="inputPrix" name="_prix"-->
						<!--													 value="--><?php //echo $this->tAwards[$i]->toString() ?><!--" disabled/>-->
						<!--									</div>-->
						<!--								</div>-->
						<!--							--><?php //endfor; ?>
						<!--						</div>-->
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Définition du processus des inscriptions</h3>
					</div>
					<div class="panel-body panel-condensed">
						<div class="row">
							<div class="col-sm-12">
								<div
									class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'process_end') ?>">
									<label for="inputEnd">Date maximale d'inscription</label>

									<div class="row">
										<div class="col-sm-3">
											<input class="form-control datepicker" type="text" id="inputEnd" name="process_end"
														 value="<?php echo plugin_vfa::toStringDateShow($this->oRegin->process_end) ?>"/>
											<span
												class="help-block"><?php echo plugin_validation::show($this->tMessage, 'process_end') ?></span>
										</div>
										<div class="col-sm-9">
											Par défaut, la date maximale de l'inscription du correspondant est dans 2 mois.
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<div class="pull-right">
							<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-ok with-text"></i>Ok</button>
							<a class="btn btn-default" href="<?php echo $this->getLink('regin::openedResponsible') ?>">
								<i class="glyphicon glyphicon-remove with-text"></i>Annuler
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
</form>
