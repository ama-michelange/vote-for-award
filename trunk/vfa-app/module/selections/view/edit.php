<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
	<input type="hidden" name="selection_id" value="<?php echo $this->oSelection->selection_id ?>"/>

	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $this->textTitle ?></h3>
		</div>
		<?php if (plugin_validation::exist($this->tMessage, 'token')): ?>
			<div class="panel-body">
				<div class="alert alert-warning clearfix">
					<p><?php echo plugin_validation::show($this->tMessage, 'token') ?>
						<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('selections::index') ?>">Fermer</a>
					</p>
				</div>
			</div>
		<?php else: ?>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'name') ?>">
							<label for="inputName">Nom
								<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="Le nom de la sélection à afficher.">
									<i class="glyphicon glyphicon-info-sign"></i>
								</span>
							</label>
							<input class="form-control" type="text" id="inputName" name="name" value="<?php echo $this->oSelection->name ?>" autofocus />
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'name') ?></span>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'year') ?>">
							<label for="inputYear">Année
								<span class="btn btn-xs btn-link" data-rel="tooltip" data-original-title="L'année de la sélection.">
									<i class="glyphicon glyphicon-info-sign"></i>
								</span>
							</label>
							<input class="form-control" type="text" id="inputYear" name="year" value="<?php echo $this->oSelection->year ?>"/>
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'year') ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<div class="pull-right">
					<button class="btn btn-primary" type="submit">
						<i class="glyphicon glyphicon-ok with-text"></i>Enregistrer
					</button>
					<?php if (trim($this->oSelection->selection_id) == false): ?>
						<a class="btn btn-default" href="<?php echo $this->getLink('selections::index') ?>"><i
								class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
					<?php else: ?>
						<a class="btn btn-default"
							href="<?php echo $this->getLink('selections::read', array('id' => $this->oSelection->selection_id)) ?>"><i
								class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</form>
