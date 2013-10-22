<form action="" method="POST" >
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="title_id" value="<?php echo $this->oTitle->getId() ?>" />
	<input type="hidden" name="idAward" value="<?php echo $this->oAward->getId() ?>" />
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">
				<?php echo $this->oAward->getTypeNameString()?>
				<small><i class="glyphicon glyphicon-chevron-right"></i></small>
				<?php echo $this->textTitle ?>
			</h3>	
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
					<a class="btn btn-sm btn-warning pull-right" href="<?php echo $this->getLink('nominees::index') ?>">Fermer</a></p>
			</div>		
		</div>
		<?php else:?>
		<div class="panel-body">
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'title_docs')?>">
				<label for="inputDocs">Albums
					<span class="btn btn-xs btn-link" data-rel="tooltip"
						data-original-title="Choisissez un ou plusieurs albums pour un titre sélectionné. Pour rechercher un album, tapez les premiers caractères de son titre. Pour ouvrir la liste déroulante, cliquez sur la partie vide du champ de saisie. Pour supprimer un album, cliquez sur la croix. Pour ajouter un 2ème album de la même série, faites comme le premier.">
						<i class="glyphicon glyphicon-info-sign"></i>
					</span>
				</label>
				<select id="inputDocs" class="form-control" name="title_docs[]"  size="13" multiple>
					<?php foreach($this->tSelectedDocs as $tDoc):?>
					<option value="<?php echo $tDoc[0] ?>" <?php if($tDoc[2]): echo 'selected'; endif;?>>
						<?php echo $tDoc[1] ?>
					</option>
					<?php endforeach;?>
				</select>
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'title_docs')?></span>
			</div>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<?php if (null == $this->oTitle->getId()):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('nominees::list',array('idAward'=>$this->oAward->getId())) ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
					<a class="btn btn-default" href="<?php echo $this->getLink('nominees::read',array('id'=>$this->oTitle->getId(),'idAward'=>$this->oAward->getId())) ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok with-text"></i>Enregistrer</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
