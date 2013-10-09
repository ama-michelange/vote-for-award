<div class="row">
<div class="col-md-12">
<div class="alert alert-info">
	<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
		<div class="alert alert-danger">
			<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
			<p><a class="btn btn-sm btn-danger"	href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a></p>
		</div>
	<?php else:?>
		<form class="form-horizontal" action="" method="POST">
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
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
				<p class="lead">Invitation prête à envoyer par mail</p>
				<dl class="dl-horizontal inverse">
					<dt>Destinataire</dt>
		  			<dd><?php echo $this->oRegistry->email ?></dd>
		  		</dl>
			</div>
			<div class="well well-white well-small">
				<p class="lead">Pour l'inscription d'un <?php echo plugin_vfa::makeSuffixTitleInvitation()?></p>
				<dl class="dl-horizontal inverse">
		  			<dt>Prix</dt>
					<?php foreach($this->tAwards as $oAward):?>
					<dd><?php echo $oAward->getTypeNameString() ?></dd>
					<?php endforeach;?>
		  			<dt>Groupe</dt>
		  			<dd><?php echo $this->oGroup->group_name ?></dd>
		  		</dl>
			</div>
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-default" type="submit" name="cancel" value="confirm">
						<i class="glyphicon glyphicon-arrow-left"></i> Précédent
					</button>
					<button class="btn btn-primary" type="submit">
						<i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Envoyer
					</button>
				</div>
			</div>
		</form>
	<?php endif;?>
</div>
</div>
</div>
