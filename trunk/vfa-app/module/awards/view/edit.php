<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="award_id" value="<?php echo $this->oAward->award_id ?>" />
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->textTitle ?></h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
			<div class="panel-body">
				<div class="alert alert-warning clearfix">
					<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
						<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('awards::index') ?>">Fermer</a>
					</p>
				</div>
			</div>
		<?php else:?>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'type')?>">
						<label>Type
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="Les albums d'un 'Prix' sont destinés aux lecteurs inscris. Les albums d'une 'Présélection' sont destinés au comité de sélection.">
								<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<div>
							<label class="radio-inline" for="inputTypePrix">
								<input type="radio" id="inputTypePrix" name="type" value="PBD" <?php if('PBD'==$this->oAward->type): echo 'checked'; endif; ?> />
								Prix
							</label>
							<label class="radio-inline" for="inputTypeSelection">
								<input type="radio" id="inputTypeSelection" name="type" value="PSBD" <?php if('PSBD'==$this->oAward->type): echo 'checked'; endif; ?> />
								Présélection
							</label>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'type')?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'public')?>">
						<label>Visibilité
							<span class="btn btn-xs btn-link" data-rel="tooltip"
									data-original-title="En privé, le prix est visible uniquement par les lecteurs inscris à ce prix. En public, les résultats du prix sont, en plus, visibles par tous les internautes visitant le site.">
								<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<div>
							<label class="radio-inline" for="inputPublic"> <input type="radio" id="inputPublic"
																									name="public" value="1" <?php if($this->oAward->public): echo 'checked'; endif; ?> /> Public
							</label> <label class="radio-inline" for="inputPrivate"> <input type="radio"
																												 id="inputPrivate" name="public" value="0"
									<?php if(!$this->oAward->public): echo 'checked'; endif; ?> /> Privé
							</label> <span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'public')?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'year')?>">
						<label for="inputName">Année
							<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="L'année officlelle du prix à afficher.">
								<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<input class="form-control" type="text" id="inputName" name="year" value="<?php echo $this->oAward->year ?>" />
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'year')?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'name')?>">
						<label for="inputName">Nom
							<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="Le nom du prix à afficher.">
								<i class="glyphicon glyphicon-info-sign"></i>
							</span>
						</label>
						<input class="form-control" type="text" id="inputName" name="name" value="<?php echo $this->oAward->name ?>" />
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'name')?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'start_date')?>">
						<label for="inputDateBegin">Date de début <span class="btn btn-xs btn-link" data-rel="tooltip"
							data-original-title="La date du jour d'ouverture du prix à partir de laquelle les premiers votes sont acceptés.">
								<i class="glyphicon glyphicon-info-sign"></i>
						</span>
						</label> <input class="form-control datepicker" type="text" id="inputDateBegin"
							name="start_date"
							value="<?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?>" /> <span
							class="help-block"><?php echo plugin_validation::show($this->tMessage, 'start_date')?></span>
					</div>
				</div>
				<div class="col-sm-6">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'end_date')?>">
						<label for="inputDateEnd">Date de fin <span class="btn btn-xs btn-link" data-rel="tooltip"
							data-original-title="La date du jour de fermeture du prix. Les votes sont acceptés jusqu'à la veille de ce jour. Ils sont clos ce jour dès minuit.">
								<i class="glyphicon glyphicon-info-sign"></i>
						</span>
						</label> <input class="form-control datepicker" type="text" id="inputDateEnd" name="end_date"
							value="<?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?>" /> <span
							class="help-block"><?php echo plugin_validation::show($this->tMessage, 'end_date')?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<?php if(trim($this->oAward->award_id)==false):?>
				<a class="btn btn-default" href="<?php echo $this->getLink('awards::index') ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
				<a class="btn btn-default"
					href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->award_id)) ?>"><i
					class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
				<button class="btn btn-primary" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
				</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
