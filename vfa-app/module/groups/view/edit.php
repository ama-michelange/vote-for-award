<div class="alert alert-info">
	<h3><?php echo $this->textTitle ?></h3>
	<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
	<div class="alert alert-error">
		<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
		<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('groups::index') ?>">Fermer</a></p>
	</div>		
	<?php else:?>
	<form class="form-horizontal" action="" method="POST" >
		<input type="hidden" name="token" value="<?php echo $this->token?>" />
		<input type="hidden" name="group_id" value="<?php echo $this->oGroup->group_id ?>" />
		<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'group_name')?>">
			<label class="control-label" for="inputName">Nom</label>
			<div class="controls">
				<input class="input-xxlarge" type="text" id="inputName" name="group_name" value="<?php echo $this->oGroup->group_name ?>" />
				<span class="help-inline"><?php echo plugin_validation::show($this->tMessage, 'group_name')?></span>
			</div>
		</div>
		<div class="<?php echo plugin_validation::addClassError('control-group', $this->tMessage, 'type')?>">
			<label class="control-label">Type</label>
			<div class="controls">
				<label class="radio-inline" for="inputTypeLecteur">
					<input type="radio" id="inputTypeLecteur" name="type" value="READER" <?php if('READER'==$this->oGroup->type): echo 'checked'; endif; ?> />
					Groupe de lecteurs
				</label>
				<label class="radio-inline" for="inputTypeBoard">
					<input type="radio" id="inputTypeBoard" name="type" value="BOARD" <?php if('BOARD'==$this->oGroup->type): echo 'checked'; endif; ?> />
					Comité de sélection
				</label>
				<span class="radio-inline help-inline"><?php echo plugin_validation::show($this->tMessage, 'type')?></span>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Enregistrer</button>
				<?php if(trim($this->oGroup->group_id)==false):?>
				<a class="btn btn-default" href="<?php echo $this->getLink('groups::index') ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
				<?php else:?>
				<a class="btn btn-default" href="<?php echo $this->getLink('groups::read',array('id'=>$this->oGroup->group_id)) ?>"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;Annuler</a>
				<?php endif;?>
			</div>
		</div>
	</form>
	<?php endif;?>
</div>
