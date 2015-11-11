<?php if ($this->oRegin->isEmpty()): ?>
	<div class="panel panel-root panel-default">
		<div class="panel-body text-center">
			<h4>Permission d'inscription invalide !</h4>
		</div>
	</div>
<?php else: ?>
	<?php echo $this->oViewShow->show(); ?>
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">
				<a data-toggle="collapse" href="#sampleMail">Exemple de mail à envoyer au correspondant</a>
				<a class="pull-right accordion-toggle" data-toggle="collapse" href="#sampleMail">
					<i data-chevron="collapse" class="glyphicon glyphicon-collapse-down"></i>
				</a>
			</h3>
		</div>
		<div id="sampleMail" class="panel-body collapse">
			<p>Le Prix de la BD INTER CE <?php echo $this->tAwards[0]->year ?> est ouvert.</p>

			<p>Pour débuter le prix, rendez-vous sur le site de vote ci-dessous en utilisant le code d'inscription suivant
				: <?php echo $this->oRegin->code ?><br>(via le menu : <strong>S'inscrire</strong> ou <strong>Inscriptions /
					S'inscrire</strong>)
			</p>

			<p>Si c'est la première fois que vous vous inscrivez à un prix sur ce site, ouvrez un compte. Sinon
				identifiez-vous avec l'identifiant et le mot de passe de votre compte.</p>

			<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
			<p>Adresse du site de vote : <a href="<?php echo $url ?>"><?php echo $url ?></a></p>

			<p>Bon prix</p>
		</div>
	</div>
<?php endif; ?>
