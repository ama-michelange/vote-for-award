<?php if (0 == count($this->tRegins)): ?>
	<div class="panel panel-root panel-default">
		<div class="panel-body text-center">
			<h4>Aucune permission d'inscriptions en cours</h4>

			<p>Pour créer la permission d'inscriptions
				<?php if (1 == count($this->tAwards)): echo 'au';
				else: echo 'aux'; endif; ?>
				<?php foreach ($this->tAwards as $award): ?>
					<?php echo $award->toString() ?>
				<?php endforeach; ?>
				<i class="glyphicon glyphicon-hand-right with-left-text with-text"></i>
				<a class="btn btn-default" href="<?php echo $this->getLink('regin::open') ?>">Créer</a>
			</p>
		</div>
	</div>
<?php else: ?>
	<?php echo $this->oViewShow->show(); ?>
	<div class="panel panel-default panel-root">
		<div class="panel-heading">
			<h3 class="panel-title">
				<a data-toggle="collapse" href="#sampleMail">Exemple de mail à envoyer aux participants</a>
				<a class="pull-right accordion-toggle" data-toggle="collapse" href="#sampleMail">
					<i data-chevron="collapse" class="glyphicon glyphicon-collapse-down"></i>
				</a>
			</h3>
		</div>
		<div id="sampleMail" class="panel-body collapse">
			<p>L'inscription <?php echo $this->tAwards[0]->toStringWithPrefix() ?> a débuté.</p>

			<p>Pour vous inscrire, rendez-vous sur le site de vote ci-dessous et utilisez le code d'inscription suivant
				: <?php echo $this->oRegin->code ?><br>(via le menu : <strong>S'inscrire</strong> ou <strong>Inscriptions /
					S'inscrire</strong>)
			</p>

			<p>Si c'est la première fois que vous vous inscrivez à un prix sur ce site, ouvrez un compte. Sinon
				identifiez-vous avec
				l'identifiant et le mot de passe de votre compte.</p>

			<p>Adresse du site de vote : <a href="<?php echo plugin_vfa::generateURLBase() ?>"><?php echo plugin_vfa::generateURLBase() ?></a></p>

			<p>Bonne lecture et bon vote</p>
		</div>
	</div>
<?php endif; ?>
