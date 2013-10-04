<form class="form-horizontal" action="" method="POST" >
	<div class="control-group">
		<label class="control-label" for="inputName">Nom du prix</label>
		<div class="controls">
			<input type="text" id="inputName" name="name" />
			<?php if($this->tMessage and isset($this->tMessage['name'])): echo implode(',',$this->tMessage['name']); endif;?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputDateBegin">Date de début du prix</label>
		<div class="controls">
			<input type="text" id="inputDateBegin" name="start_date" class="datepicker" />
			<?php if($this->tMessage and isset($this->tMessage['start_date'])): echo implode(',',$this->tMessage['start_date']); endif;?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputDateEnd">Date de fin du prix</label>
		<div class="controls">
			<input type="text" id="inputDateEnd" name="end_date" class="datepicker" />
			<?php if($this->tMessage and isset($this->tMessage['end_date'])): echo implode(',',$this->tMessage['end_date']); endif;?>
		</div>
	</div>

	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

	<div class="control-group">
		<div class="controls">
			<button class="btn btn-primary" type="submit">Ajouter</button>
			<a class="btn btn-default" href="<?php echo $this->getLink('awards::index') ?>">Annuler</a>
		</div>
	</div>
</form>

