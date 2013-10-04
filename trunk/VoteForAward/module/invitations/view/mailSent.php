<div class="alert alert-success">
	<form class="form-horizontal" action="" method="POST">
		<input type="hidden" name="phase" value="<?php echo $this->oRegistry->phase ?>" />
		<input type="hidden" name="type" value="<?php echo $this->oRegistry->type ?>" /> 
		<input type="hidden" name="email" value="<?php echo $this->oRegistry->email ?>" /> 
		<?php if ($this->oRegistry->award_id):?>
			<input type="hidden" name="award_id" value="<?php echo $this->oRegistry->award_id ?>" /> 
		<?php endif;?>
		<?php if ($this->oRegistry->awards_ids):?>
			<?php foreach($this->oRegistry->awards_ids as $id):?>
				<input type="hidden" name="awards_ids[]" value="<?php echo $id ?>" /> 
			<?php endforeach;?>
		<?php endif;?>
		<?php if ($this->oRegistry->group_id):?>
			<input type="hidden" name="group_id" value="<?php echo $this->oRegistry->group_id ?>" /> 
		<?php endif;?>
		
		<div class="well well-white well-small">
			<p class="lead">Invitation envoyée</p>
			<dl class="dl-horizontal inverse">
				<dt>Destinataire</dt>
	  			<dd><strong><?php echo $this->oRegistry->email ?></strong></dd>
				<dt>En copie</dt>
	  			<dd><?php echo _root::getAuth()->getAccount()->getUser()->email ?></dd>
	  		</dl>
		</div>
		<div class="well well-white well-small">
			<p class="lead">Pour l'inscrire comme <?php echo plugin_vfa::makeSuffixTitleInvitation()?></p>
			<dl class="dl-horizontal inverse">
	  			<dt>Prix</dt>
				<?php foreach($this->tAwards as $oAward):?>
				<dd><?php echo $oAward->getTypeNameString() ?></dd>
				<?php endforeach;?>
	  			<dt>Groupe</dt>
	  			<dd><?php echo $this->oGroup->group_name ?></dd>
	  		</dl>
		</div>
		<div class="well well-small">
			<p class="lead">Génération</p>
			<dl class="dl-horizontal">
				<dt class="inverse">URL d'inscription</dt>
	  			<dd><?php echo plugin_vfa::generateURLInvitation($this->oRegistry->invit)?></dd>
 				<dt class="inverse">Accès de test</dt>
	  			<dd><a href="<?php echo plugin_vfa::generateURLInvitation($this->oRegistry->invit)?>" target="_new">Test</a></dd>
  			</dl>
		</div>
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-default" type="submit">
					<i class="glyphicon glyphicon-repeat"></i>&nbsp;Recommencer
				</button>
			</div>
		</div>
	</form>
</div>
