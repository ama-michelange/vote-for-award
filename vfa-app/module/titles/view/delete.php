<div class="well well-small alert clearfix">
	<h3>Supprimer un titre</h3>
	<?php echo $this->oViewShow->show(); ?>
	<form class="form-horizontal" action="" method="POST">
		<input type="hidden" name="token" value="<?php echo $this->token ?>"/>
		<?php if ($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif; ?>
		<?php if ($this->ok): ?>
			<button class="btn btn-warning" type="submit">
				<i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Confirmer la suppression
			</button>
			<a class="btn btn-success" href="<?php echo $this->getLink('titles::index') ?>"><i
					class="glyphicon glyphicon-remove glyphicon glyphicon-white"></i> Annuler</a>
		<?php else : ?>
			<div class="alert alert-success clearfix">
				<a class="btn btn-success " href="<?php echo $this->getLink('titles::index') ?>"><i
						class="glyphicon glyphicon-remove glyphicon glyphicon-white"></i> Annuler</a> <strong>Suppression
					interdite !</strong> Ce titre est encore associé à un prix.
			</div>
		<?php endif; ?>
	</form>
</div>
