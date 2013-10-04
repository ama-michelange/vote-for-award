<div class="alert alert-info">
	<div class="row">
		<h3><?php echo $this->textTitle ?></h3>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="alert alert-error">
			<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
			<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('awards::index') ?>">Fermer</a></p>
		</div>		
		<?php else:?>
		<form class="form-horizontal" action="" method="POST" >
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<input type="hidden" name="award_id" value="<?php echo $this->oAward->award_id ?>" />
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'name')?>">
				<label class="control-label" for="inputName">Nom
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Nom" 
						data-content="Le nom du prix affiché.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<input class="col-md-7" type="text" id="inputName" name="name" value="<?php echo $this->oAward->name ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'name')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'type')?>">
				<label class="control-label">Type
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Type" 
						data-content="Un prix contient une partie des albums d'une présélection de la même période. Les lecteurs inscris votent et notent les albums de ce prix. Une présélection contient les meilleurs albums d'une période choisis par les libraires. C'est le comité de sélection qui note les albums de la présélection pour déterminer les albums du prix.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<label class="radio-inline" for="inputTypePrix">
						<input class="inline" type="radio" id="inputTypePrix" name="type" value="PBD" <?php if('PBD'==$this->oAward->type): echo 'checked'; endif; ?> />
						Prix
					</label>
					<label class="radio-inline" for="inputTypeSelection">
						<input type="radio" id="inputTypeSelection" name="type" value="PSBD" <?php if('PSBD'==$this->oAward->type): echo 'checked'; endif; ?> />
						Présélection
					</label>
					<span class="radio-inline help-inline"><?php echo plugin_validation::show($this->tMessage, 'type')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'public')?>">
				<label class="control-label">Affichage
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Affichage" 
						data-content="En privé, le prix est visible uniquement aux lecteurs inscris à ce prix. En public, les résultats du prix sont, en plus, visibles par tous les internautes visitant le site.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<label class="radio-inline" for="inputPublic">
						<input class="inline" type="radio" id="inputPublic" name="public" value="1" <?php if($this->oAward->public): echo 'checked'; endif; ?> />
						Public
					</label>
					<label class="radio-inline" for="inputPrivate">
						<input type="radio" id="inputPrivate" name="public" value="0" <?php if(!$this->oAward->public): echo 'checked'; endif; ?> />
						Privé
					</label>
					<span class="radio-inline help-inline"><?php echo plugin_validation::show($this->tMessage, 'public')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'start_date')?>">
				<label class="control-label" for="inputDateBegin">Date de début
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Date de début" 
						data-content="La date du jour d'ouverture du prix. La date à partir de laquelle on accepte les premiers votes.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<input type="text" id="inputDateBegin" name="start_date" class="datepicker" value="<?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'start_date')?></span>
				</div>
			</div>
			<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'end_date')?>">
				<label class="control-label" for="inputDateEnd">Date de fin
					<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Date de fin" 
						data-content="La date du jour de fermeture du prix. Les votes sont acceptés jusqu'à la veillle de ce jour. Ils sont clos ce jour dès minuit.">
						<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
					</a>
				</label>
				<div class="controls">
					<input type="text" id="inputDateEnd" name="end_date" class="datepicker" value="<?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?>" />
					<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'end_date')?></span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i>&nbsp;&nbsp;Enregistrer</button>
					<?php if(trim($this->oAward->award_id)==false):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('awards::index') ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
					<?php else:?>
					<a class="btn btn-default" href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->award_id)) ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
					<?php endif;?>
				</div>
			</div>
		</form>
		<?php endif;?>
	</div>
</div>
