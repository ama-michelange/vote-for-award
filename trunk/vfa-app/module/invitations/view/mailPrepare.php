<div class="row">
<div class="col-md-12">
<!-- <div class="alert alert-info col-md-offset-2 col-md-8"> -->
<div class="alert alert-info">
	<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
	<div class="alert alert-error">
		<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
		<p><a class="btn btn-sm btn-danger"	href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a></p>
	</div>
	<?php else:?>
		<?php if (($this->countAwards == 0) || ($this->countGroups == 0)): ?>
			<div class="alert alert-error">L'inscription n'est pas possible !</div>
		<?php endif;?>
		<?php if(plugin_validation::exist($this->tMessage, 'doublon')):?>
			<div class="alert alert-error">Cette invitation existe déjà !</div>
		<?php endif;?>
		<form class="form-horizontal" action="" method="POST">
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<input type="hidden" name="phase" value="<?php echo $this->oRegistry->phase ?>" />
			<input type="hidden" name="type" value="<?php echo $this->oRegistry->type ?>" /> 
			
			<fieldset>
				<legend>Invitation à envoyer par mail</legend>
				<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'email')?>">
					<label class="control-label" for="inputEmail">Adresse email du destinataire</label>
					<div class="controls">
						<input class="input-block-level" type="text" id="inputEmail" name="email" autofocus="autofocus" value="<?php echo $this->oRegistry->email ?>" /> 
						<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'email')?></span>
					</div>
				</div>
			</fieldset>
			<?php /* 
			<fieldset>
				<legend><a class="accordion-toggle" data-toggle="collapse" href="#emails"><small>Ou bien</small> Inviter plusieurs personnes</a></legend>
				<div id="emails" class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'emails')?> collapse in">
					<label class="control-label" for="inputEmails">Liste des adresses emails</label>
					<div class="controls">
						<textarea id="inputEmails" class="input-block-level" name="emails" rows="5">
							<?php echo $this->oRegistry->emails ?>
						</textarea>
						<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'emails')?></span>
						<span class="help-inline">Séparez chaque adresse par un retour chariot.<br />
							Exemple : <br />premier.email@serveur.fr<br />deuxieme.email@serveur.fr<br />troisieme.email@serveur.fr
						</span>
					</div>
				</div>
			</fieldset>
			*/ ?>
			<fieldset>
				<legend>Pour l'inscription d'un <strong><?php echo plugin_vfa::makeSuffixTitleInvitation()?></strong></legend>
				<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, array('award_id','awards'))?>">
					<label class="control-label" for="inputAwards">Prix</label>
					<div class="controls">
						<?php if ($this->countAwards == 0): ?>
							<span class="input-block-level uneditable-input"></span>
							<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'awards')?></span>
						<?php elseif ($this->countAwards == 1):?>
							<input type="hidden" name="award_id" value="<?php echo $this->oAward->award_id ?>" />
							<span class="input-block-level uneditable-input"><?php echo $this->oAward->getTypeNameString() ?></span>
						<?php else:?>
							<select class="input-block-level" id="inputAwards" name="awards_ids[]"
								size="10" multiple>
								<?php foreach($this->tSelectedAwards as $tAward):?>
								<option value="<?php echo $tAward[0] ?>"
								<?php if($tAward[2]): echo 'selected'; endif;?>>
									<?php echo $tAward[1] ?>
								</option>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'award_id')?></span>
					</div>
				</div>
				<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, array('group_id','groups')) ?>">
					<label class="control-label" for="inputGroups">Groupe</label>
					<div class="controls">
						<?php if ($this->countGroups == 0): ?>
							<span class="input-block-level uneditable-input"></span>
							<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'groups')?></span>
						<?php elseif ($this->countGroups == 1):?>
							<input type="hidden" name="group_id" value="<?php echo $this->oGroup->group_id ?>" />
							<span class="input-block-level uneditable-input"><?php echo $this->oGroup->group_name ?></span>
						<?php else:?>
							<select class="input-block-level" id="inputGroups" name="group_id" data-placeholder="Choisir">
								<?php foreach($this->tSelectedGroups as $tGroup):?>
									<?php if (-1 == $tGroup[0]): ?>
										<option></option>
									<?php else:?>
										<option value="<?php echo $tGroup[0] ?>" <?php if($tGroup[2]): echo 'selected'; endif;?>>
											<?php echo $tGroup[1] ?>
										</option>
									<?php endif;?>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'group_id')?></span>
					</div>
				</div>
			</fieldset>
	
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-default" type="submit" name="cancel" value="prepare">
						<i class="glyphicon glyphicon-remove"></i> Annuler
					</button>
					<?php if (($this->countAwards > 0) && ($this->countGroups > 0)): ?>
					<button class="btn btn-primary" type="submit">
						Suivant &nbsp;&nbsp;<i class="glyphicon glyphicon-arrow-right glyphicon glyphicon-white"></i> 
					</button>
					<?php endif;?>
				</div>
			</div>
		</form>
	<?php endif;?>
</div>
</div>
<?php /*
<div class="col-md-4">
Ac ne quis a nobis hoc ita dici forte miretur, quod alia quaedam in hoc facultas sit ingeni, neque haec dicendi ratio aut disciplina, ne nos quidem huic uni studio penitus umquam dediti fuimus. Etenim omnes artes, quae ad humanitatem pertinent, habent quoddam commune vinculum, et quasi cognatione quadam inter se continentur.

Advenit post multos Scudilo Scutariorum tribunus velamento subagrestis ingenii persuasionis opifex callidus. qui eum adulabili sermone seriis admixto solus omnium proficisci pellexit vultu adsimulato saepius replicando quod flagrantibus votis eum videre frater cuperet patruelis, siquid per inprudentiam gestum est remissurus ut mitis et clemens, participemque eum suae maiestatis adscisceret, futurum laborum quoque socium, quos Arctoae provinciae diu fessae poscebant.

Sed laeditur hic coetuum magnificus splendor levitate paucorum incondita, ubi nati sunt non reputantium, sed tamquam indulta licentia vitiis ad errores lapsorum ac lasciviam. ut enim Simonides lyricus docet, beate perfecta ratione vieturo 
</div>
*/ ?>
</div>
