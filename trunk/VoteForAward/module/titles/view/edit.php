<div class="alert alert-info">
	<h3><?php echo $this->textTitle ?></h3>
	<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
	<div class="alert alert-error">
		<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
		<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('titles::index') ?>">Fermer</a></p>
	</div>		
	<?php else:?>
	<form class="form-horizontal" action="" method="POST" >
		<input type="hidden" name="token" value="<?php echo $this->token?>" />
		<input type="hidden" name="title_id" value="<?php echo $this->oTitle->title_id ?>" />
		<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'title_docs')?>">
			<label class="control-label" for="inputDocs">Albums du titre</label>
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
				<a class="btn btn-default" href="<?php echo $this->getLink('titles::index') ?>"><i class="glyphicon glyphicon-remove"></i> Annuler</a>
			</div>
		</div>
	</form>
	<?php endif;?>
</div>
