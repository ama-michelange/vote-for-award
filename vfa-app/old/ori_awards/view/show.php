<div class="row">
<div class="well well-small form-horizontal">
	<div class="control-group">
		<label class="control-label">Nom du prix</label>
		<div class="controls">
			<span class="uneditable-input"><?php echo $this->oAward->name ?></span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputDateBegin">Date de début du prix</label>
		<div class="controls">
			<span class="uneditable-input"><?php echo $this->oAward->start_date ?></span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputDateEnd">Date de fin du prix</label>
		<div class="controls">
			<span class="uneditable-input"><?php echo $this->oAward->end_date ?></span>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-default" href="<?php echo $this->getLink('awards::index') ?>">Fermer</a>
		</div>
	</div>
</div>
</div>