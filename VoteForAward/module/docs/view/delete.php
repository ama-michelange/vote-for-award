<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">Supprimer un album</h3>
	</div>
	<div class="panel-body">
		<?php echo $this->oViewShow->show();?>
	</div>
	<div class="panel-footer">
		<form class="form-horizontal" action="" method="POST" >
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>
			<?php if ($this->ok):?>
			<button class="btn btn-danger" type="submit"><i class="glyphicon glyphicon-ok glyphicon glyphicon-white"></i> Confirmer la suppression</button>
			<a class="btn btn-success" href="<?php echo $this->getLink('docs::index') ?>"><i class="glyphicon glyphicon-remove glyphicon glyphicon-white"></i> Annuler</a>
			<?php else :?>
			<div class="alert alert-danger clearfix">
				<strong>Suppression interdite !</strong> Cet album est encore associé à un prix.
				<a class="btn btn-success pull-right" href="<?php echo $this->getLink('docs::index') ?>"><i class="glyphicon glyphicon-remove glyphicon glyphicon-white"></i> Annuler</a>
			</div>
			<?php endif;?>
		</form>
	</div>
</div>
