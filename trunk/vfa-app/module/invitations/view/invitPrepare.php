<form action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" /> <input type="hidden"
		name="phase" value="<?php echo $this->oRegistry->phase ?>" /> <input type="hidden" name="type"
		value="<?php echo $this->oRegistry->type ?>" />


	<div class="panel panel-info">
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right"	href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a>
				</p>
			</div>
		</div>
		<?php else:?>
		<div class="panel-body panel-condensed">
			<?php if (($this->countAwards == 0) || ($this->countGroups == 0)): ?>
				<div class="alert alert-danger">L'inscription n'est pas possible !</div>
			<?php endif;?>
			<?php if(plugin_validation::exist($this->tMessage, 'doublon')):?>
				<div class="alert alert-danger">Cette invitation existe déjà !</div>
			<?php endif;?>
					
			<div class="panel panel-info panel-inner">
				<div class="panel-heading">
					<h3 class="panel-title">Invitation à envoyer par mail</h3>
				</div>
				<div class="panel-body panel-condensed">
					<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'email')?>">
						<label for="inputEmail">Adresse email du destinataire</label>
						<input class="form-control" type="text" id="inputEmail" name="email" autofocus="autofocus" value="<?php echo $this->oRegistry->email ?>" /> 
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
					</div>
				</div>
			</div>

			<div class="panel panel-info panel-inner">
				<div class="panel-heading">
					<h3 class="panel-title">
						Pour l'inscription d'un <strong><?php echo plugin_vfa::makeSuffixTitleInvitation()?></strong>
					</h3>
				</div>
				<div class="panel-body panel-condensed">
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, array('award_id','awards'))?>">
						<label for="inputAwards">Prix</label>
						<?php if ($this->countAwards == 0): ?>
							<input class="form-control" type="text" disabled="disabled" /> 
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'awards')?></span>
						<?php elseif ($this->countAwards == 1):?>
							<input type="hidden" name="award_id" value="<?php echo $this->oAward->award_id ?>" /> 
							<input class="form-control" type="text" disabled="disabled"	value="<?php echo $this->oAward->getTypeNameString() ?>"> 
						<?php else:?>
							<select class="form-control" id="inputAwards" name="awards_ids[]" size="10" multiple>
								<?php foreach($this->tSelectedAwards as $tAward):?>
									<option value="<?php echo $tAward[0] ?>" <?php if($tAward[2]): echo 'selected'; endif;?>>
										<?php echo $tAward[1]?>
									</option>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'award_id')?></span>
					</div>
					<div
						class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, array('group_id','groups')) ?>">
						<label for="inputGroups">Groupe</label>
						<?php if ($this->countGroups == 0): ?>
							<input class="form-control" type="text" disabled="disabled" /> 
							<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'groups')?></span>
						<?php elseif ($this->countGroups == 1):?>
							<input type="hidden" name="group_id" value="<?php echo $this->oGroup->group_id ?>" /> 
							<input class="form-control" type="text" disabled="disabled"	value="<?php echo $this->oGroup->group_name ?>">
						<?php else:?>
							<select class="form-control" id="inputGroups" name="group_id" data-placeholder="Choisir">
								<?php foreach($this->tSelectedGroups as $tGroup):?>
									<?php if (-1 == $tGroup[0]): ?>
											<option></option>
									<?php else:?>
										<option value="<?php echo $tGroup[0] ?>" <?php if($tGroup[2]): echo 'selected'; endif;?>>
											<?php echo $tGroup[1]?>
										</option>
									<?php endif;?>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'group_id')?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button class="btn btn-default" type="submit" name="cancel" value="prepare"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</button>
				<?php if (($this->countAwards > 0) && ($this->countGroups > 0)): ?>
					<button class="btn btn-primary" type="submit">Suivant &nbsp;&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></button>
				<?php endif;?>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
