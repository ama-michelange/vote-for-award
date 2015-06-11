<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="type" value="<?php echo $this->oRegin->type ?>"/>
	<input type="hidden" name="code" value="<?php echo $this->oRegin->code ?>"/>
	<input type="hidden" name="state" value="<?php echo $this->oRegin->state ?>"/>
	<input type="hidden" name="created_user_id" value="<?php echo $this->oRegin->created_user_id ?>"/>
	<input type="hidden" name="awards_ids" value="<?php echo $this->oRegin->awards_ids ?>"/>
	<input type="hidden" name="group_id" value="<?php echo $this->oRegin->group_id ?>"/>

	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Permission aux lecteurs du groupe <?php echo $this->oGroup->toString() ?></h3>
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
						<p>La permission des inscriptions permet aux lecteurs de s'inscrire au prix pour participer aux votes et le
							cas échéant d'ouvrir un compte.</p>

						<p>Lors de leur inscription, les lecteurs ont besoin d'un code d'inscription que vous allez leur founir.
							Ce code est associé à votre groupe : CE, association, ...<br/>
							Il sert à associer la personne qui l'utilise à votre groupe et au prix en cours lorsqu'elle ouvre un
							compte ou
							s'identifie sur le site.</p>

						<p>Vous obtiendrez le code d'inscription dans l'écran suivant qu'il faudra transmettre aux participants.</p>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Caractéristique de l'inscription du lecteur</h3>
					</div>
					<div class="panel-body panel-condensed">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="inputGroup">Affectation au groupe</label>
									<input class="form-control" type="text" id="inputGroup" name="_group"
												 value="<?php echo $this->oGroup->toString() ?>" disabled/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="inputPrix">Inscription au prix</label>
									<input class="form-control" type="text" id="inputPrix" name="_prix"
												 value="<?php echo $this->tAwards[0]->toString() ?>" disabled/>
								</div>
							</div>
							<?php for ($i = 1; $i < count($this->tAwards); $i++) : ?>
								<div class="col-sm-4">
									<div class="form-group">
										<label for="inputPrix">Inscription au prix</label>
										<input class="form-control" type="text" id="inputPrix" name="_prix"
													 value="<?php echo $this->tAwards[$i]->toString() ?>" disabled/>
									</div>
								</div>
							<?php endfor; ?>
						</div>
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
									<label for="inputEnd">Date de fin des inscriptions</label>

									<div class="row">
										<div class="col-sm-3">
											<input class="form-control datepicker" type="text" id="inputEnd" name="process_end"
														 value="<?php echo plugin_vfa::toStringDateShow($this->oRegin->process_end) ?>"/>
											<span
												class="help-block"><?php echo plugin_validation::show($this->tMessage, 'process_end') ?></span>
										</div>
										<div class="col-sm-9">
											Par défaut, la date maximale des inscriptions est la date de fin du prix mais vous pouvez la
											réduire à votre convenance.
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'process') ?>">
									<label>Validation des inscriptions</label>

									<div class="row">
										<div class="col-sm-3">
											<label class="checkbox-inline" for="inputValidate">
												<input type="checkbox" id="inputValidate" name="novalidate" value="true"
													<?php if (true == $this->novalidate): echo 'checked'; endif; ?> />
												Pas de validation
											</label>
											<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'process') ?></span>
										</div>
										<div class="col-sm-9">
											Par défaut, vous devez valider chaque inscription. Cette étape supplémentaire permet d'identifier
											les
											participants avant de leur donner le droit de vote. C'est utile pour gérer les inscrits et les
											futurs
											prêts,
											par exemple, mais surtout pour éviter les doubles inscriptions : une même personne qui s'inscrit
											plusieurs
											fois avec un nom différent.<br>
											Si vous cochez <strong>Pas de validation</strong>, toutes les personnes possédant le code
											d'inscription
											pourront s'inscrire sans votre intervention. La confiance doit régner ...
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<div class="pull-right">
							<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-ok with-text"></i>Ok</button>
							<a class="btn btn-default" href="<?php echo $this->getLink('regin::opened') ?>">
								<i class="glyphicon glyphicon-remove with-text"></i>Annuler
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</form>
