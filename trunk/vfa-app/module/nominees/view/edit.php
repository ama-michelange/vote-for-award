<div class="alert alert-info">
	<h4>
		Une sélection de 
		<?php if(_root::getACL()->permit('awards::read')):?>
			<a	href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->getId()))?>">
				<?php echo $this->oAward->getTypeNameString() ?>
			</a>
		<?php else:?>
			<?php echo $this->oAward->getTypeNameString() ?>
		<?php endif;?>
	</h4>
	<h3><?php echo $this->textTitle ?></h3>
	<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
	<div class="alert alert-error">
		<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
		<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('titles::index') ?>">Fermer</a></p>
	</div>		
	<?php else:?>
	<form class="form-horizontal" action="" method="POST" >
		<input type="hidden" name="token" value="<?php echo $this->token?>" />
		<input type="hidden" name="title_id" value="<?php echo $this->oTitle->getId() ?>" />
		<input type="hidden" name="idAward" value="<?php echo $this->oAward->getId() ?>" />
		<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'title_docs')?>">
			<label class="control-label" for="inputDocs">Albums 
				<a href="#" class="btn btn-xs btn-link" rel="popover" data-original-title="Albums" 
					data-content="Choisissez un ou plusieurs albums pour un titre sélectionné. Pour rechercher un album, tapez les premiers caractères de son titre. Pour ouvrir la liste déroulante, cliquez sur la partie vide du champ de saisie. Pour supprimer un album, cliquez sur la croix. Pour ajouter un 2ème album de la même série, faites comme le premier.">
					<i class="glyphicon glyphicon-question-sign glyphicon glyphicon-white"></i>
				</a>
			</label>
			<div class="controls">
				<select id="inputDocs" name="title_docs[]" class="input-xxlarge" size="13" multiple>
					<?php foreach($this->tSelectedDocs as $tDoc):?>
					<option value="<?php echo $tDoc[0] ?>" <?php if($tDoc[2]): echo 'selected'; endif;?>>
						<?php echo $tDoc[1] ?>
					</option>
					<?php endforeach;?>
				</select>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'title_docs')?></span>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Enregistrer</button>
				<?php if (null == $this->oTitle->getId()):?>
				<a class="btn btn-default" href="<?php echo $this->getLink('nominees::list',array('idAward'=>$this->oAward->getId())) ?>"><i class="glyphicon glyphicon-remove"></i> Annuler</a>
				<?php else:?>
				<a class="btn btn-default" href="<?php echo $this->getLink('nominees::read',array('id'=>$this->oTitle->getId(),'idAward'=>$this->oAward->getId())) ?>"><i class="glyphicon glyphicon-remove"></i> Annuler</a>
				<?php endif;?>
			</div>
		</div>
	</form>
	<?php endif;?>
</div>
