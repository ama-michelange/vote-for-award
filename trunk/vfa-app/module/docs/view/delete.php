<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">Supprimer l'album : <?php echo $this->oViewShow->oDoc->toString() ?></h3>
	</div>
	<div class="panel-body panel-inner">
		<?php echo $this->oViewShow->show();?>
	</div>
	<div class="panel-footer clearfix">
		<form action="" method="POST">
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>
			<?php if ($this->ok):?>
				<div class="pull-right">
					<a class="btn btn-default" href="<?php echo $this->getLink('docs::index') ?>"><i
						class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
					<button class="btn btn-danger" type="submit">
						<i class="glyphicon glyphicon-ok with-text"></i>Confirmer la suppression
					</button>
				</div>
			<?php else :?>
				<strong>Suppression interdite !</strong> Cet album est encore associé à une sélection.
				<a	class="btn btn-default pull-right" href="<?php echo $this->getLink('docs::index') ?>">
					<i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
			<?php endif;?>
		</form>
	</div>
</div>
