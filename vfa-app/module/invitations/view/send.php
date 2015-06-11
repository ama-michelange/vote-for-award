<?php
if ($this->oViewShow->oInvitation->sent) {
	$color = 'success';
	$title = 'Invitation envoyée';
	$button = 0;
} elseif ($this->oViewShow->oInvitation->notSent) {
	$color = 'warning';
	$title = 'Problème pour envoyer l\'invitation par mail';
	$button = 1;
} else {
	$color = 'info';
	$title = 'Envoyer une invitation par mail';
	$button = 2;
}
?>


<div class="panel panel-<?php echo $color ?> panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $title ?></h3>
	</div>
	<div class="panel-body panel-inner">
		<?php echo $this->oViewShow->show(); ?>
	</div>
	<div class="panel-footer clearfix">
		<form action="" method="POST">
			<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
			<?php if ($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif; ?>
			<div class="pull-right">
				<a class="btn btn-default"
					 href="<?php echo $this->getLink('invitations::read', array('id' => _root::getParam('id'))) ?>"><i
						class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php if ($button == 0): ?>
					<a class="btn btn-success" href="<?php echo $this->getLink('invitations::index') ?>"><i
							class="glyphicon glyphicon-ok with-text"></i>Fermer</a>
				<?php elseif ($button == 1): ?>
					<a class="btn btn-warning" href="<?php echo $this->getLink('invitations::index') ?>"><i
							class="glyphicon glyphicon-ok with-text"></i>Fermer</a>
					<?php
				else: ?>
					<button class="btn btn-info" type="submit">
						<i class="glyphicon glyphicon-envelope with-text"></i>Confirmer l'envoi
					</button>
				<?php endif; ?>
			</div>
		</form>
	</div>
</div>
