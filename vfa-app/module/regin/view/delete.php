<div class="panel panel-danger panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">Fermeture des inscriptions</h3>
	</div>
	<div class="panel-body panel-inner">
		<p>La fermeture des inscriptions supprime le code d'inscription correspondant. Les lecteurs ne pourront plus utiliser ce code pour s'inscrire.</p>
		<?php echo $this->oViewShow->show();?>
	</div>
	<div class="panel-footer clearfix">
		<form action="" method="POST">
			<input type="hidden" name="token" value="<?php echo $this->token?>" />
			<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>
			<div class="pull-right">
				<a class="btn btn-default" href="<?php echo $this->getLink('regin::opened') ?>">
					<i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<button class="btn btn-danger" type="submit">
					<i class="glyphicon glyphicon-ok with-text"></i>Confirmer la fermeture
				</button>
			</div>
		</form>
	</div>
</div>
