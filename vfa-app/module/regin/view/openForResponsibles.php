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
			<h3 class="panel-title">Permission au correspondant</h3>
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
							<li>De devenir <strong>Correspondant</strong>, c'est à dire, de gérer le prix, d'inscrire les lecteurs
								pour participer aux votes, ...
							</li>
							<li>De s'inscrire au prix pour participer aux votes</li>
							<li>Et le cas échéant d'ouvrir un compte</li>
						</ul>
						</p>

						<p>Lors de cette inscription, le correspondant a besoin d'un code d'inscription que vous allez lui
							founir.<br/>
							Il sert à donner les droits de correspondant, de l'associer à son groupe et au prix en cours lorsqu'il
							ouvre un compte ou s'identifie sur le site. Ce code est unique pour un groupe : CE, association, ...</p>

						<p>Vous obtiendrez le code d'inscription dans l'écran suivant qu'il faudra transmettre à la personne adéquate.</p>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Caractéristique de l'inscription du correspondant</h3>
					</div>
					<div class="panel-body panel-condensed">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="inputNewGroup">Affectation au groupe</label>
									<input class="form-control" type="text" id="inputNewGroup" name="_group"
												 value="<?php echo $this->oGroup->toString() ?>" />
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
