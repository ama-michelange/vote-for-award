<div class="row">
	<form class="form-horizontal alert alert-info" action="" method="POST" >
		<?php foreach($this->tColumn as $sColumn):?>
			<?php if( !in_array($sColumn,$this->tId)) continue;?>
			<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oAward->$sColumn ?>" />
			<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
		<?php endforeach;?>	
		<h3>Modifier un prix</h3>
		<div class="control-group">
			<label class="control-label" for="inputName">Nom du prix</label>
			<div class="controls">
				<input type="text" id="inputName" name="name" value="<?php echo $this->oAward->name ?>" />
				<?php if($this->tMessage and isset($this->tMessage['name'])): echo implode(',',$this->tMessage['name']); endif;?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputDateBegin">Date de début du prix</label>
			<div class="controls">
				<input type="text" id="inputDateBegin" name="start_date" class="datepicker" value="<?php echo $this->oAward->start_date ?>" />
				<?php if($this->tMessage and isset($this->tMessage['start_date'])): echo implode(',',$this->tMessage['start_date']); endif;?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputDateEnd">Date de fin du prix</label>
			<div class="controls">
				<input type="text" id="inputDateEnd" name="end_date" class="datepicker" value="<?php echo $this->oAward->end_date ?>" />
				<?php if($this->tMessage and isset($this->tMessage['end_date'])): echo implode(',',$this->tMessage['end_date']); endif;?>
			</div>
		</div>
		<input type="hidden" name="token" value="<?php echo $this->token?>" />
		<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit">Enregistrer</button>
				<a class="btn btn-default" href="<?php echo $this->getLink('awards::index') ?>">Annuler</a>
			</div>
		</div>
	</form>
</div>
