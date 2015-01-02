<?php if (0 == count($this->tRegins)): ?>
	<div class="panel panel-root panel-default">
		<div class="panel-body text-center">
			<h4>Les inscriptions des lecteurs ne sont pas ouvertes !</h4>
			<p>Pour ouvrir les inscriptions
				<?php if (1 == count($this->tAwards)): echo 'au';
				else: echo 'aux'; endif; ?>
				<?php foreach ($this->tAwards as $award): ?>
					<?php echo $award->toString() ?>
				<?php endforeach; ?>
				<i class="glyphicon glyphicon-hand-right with-left-text with-text"></i>
				<a class="btn btn-default" href="<?php echo $this->getLink('regin::open') ?>">Ouvrir</a>
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
					<i data-chevron="collapse"	class="glyphicon glyphicon-collapse-down"></i>
				</a>
			</h3>
		</div>
		<div id="sampleMail" class="panel-body collapse">
			<p>Bonjour</p>
			<p>Les inscriptions pour voter au Prix de la BD INTER CE <?php echo $this->tAwards[0]->year ?> sont ouvertes.</p>
			<p>Pour vous inscrire, rendez-vous sur le site de vote ci-dessous en utilisant le code d'inscription suivant
				: <?php echo $this->oRegin->code ?><br>(via le menu : Inscriptions / S'inscrire)</p>
			<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
			<p>Adresse du site de vote : <a href="<?php echo $url ?>"><?php echo $url ?></a></p>
			<p>Bonne lecture et bon vote</p>
		</div>
	</div>
<?php endif; ?>
