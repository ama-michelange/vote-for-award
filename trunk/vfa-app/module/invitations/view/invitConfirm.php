<form class="form-horizontal" action="" method="POST">
	<input type="hidden" name="token" value="<?php echo $this->token?>" /> <input type="hidden"
		name="phase" value="<?php echo $this->oRegistry->phase ?>" /> <input type="hidden" name="type"
		value="<?php echo $this->oRegistry->type ?>" /> <input type="hidden" name="email"
		value="<?php echo $this->oRegistry->email ?>" /> 
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
	
	<div class="panel panel-info panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">Invitation
				<small>pour l'inscription d'un</small>
				<strong><?php echo plugin_vfa::makeSuffixTitleInvitation() ?></strong>
			</h3>
		</div>
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
			<div class="panel-body">
			<div class="alert alert-warning clearfix">
				<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
						<a class="btn btn-sm btn-warning pull-right"
						href="<?php echo $this->getLink(_root::getParamNav()) ?>">Fermer</a>
				</p>
			</div>
		</div>
		<?php else:?>
			<div class="panel-body panel-condensed">
				<dl class="dl-horizontal">
					<dt>Destinataire</dt>
					<dd><?php echo $this->oRegistry->email ?></dd>
					<!--					</dl>-->
					<!--					<dl class="dl-horizontal">-->
					<dt>Prix</dt>
					<?php foreach ($this->tAwards as $oAward): ?>
						<dd><?php echo $oAward->toString() ?></dd>
					<?php endforeach; ?>
					<dt>Groupe</dt>
					<dd><?php echo $this->oGroup->group_name ?></dd>
				</dl>
			</div>
		<div class="panel-footer clearfix">
			<button class="btn btn-default" type="submit" name="cancel" value="confirm">
				<i class="glyphicon glyphicon-arrow-left with-text"></i>Précédent
			</button>
			<div class="pull-right">
				<button class="btn btn-primary" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Envoyer
				</button>
			</div>
		</div>
		<?php endif;?>
	</div>
</form>
