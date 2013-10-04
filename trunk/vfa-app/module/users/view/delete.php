<div class="well well-small alert clearfix">
	<h3>Supprimer un utilisateur</h3>
	<?php echo $this->oViewShow->show();?>
	<form class="form-horizontal" action="" method="POST" >
		<input type="hidden" name="token" value="<?php echo $this->token?>" />
		<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>
		<button class="btn btn-warning" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Confirmer la suppression</button>
		<a class="btn btn-success" href="<?php echo $this->getLink('users::index') ?>"><i class="glyphicon glyphicon-remove glyphicon glyphicon-white"></i> Annuler</a>
	</form>
</div>
