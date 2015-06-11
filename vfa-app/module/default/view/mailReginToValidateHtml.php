<html>
<body>
<p>L'utilisateur <?php echo $this->oUser->toStringPublic() ?> vient de s'inscrire au Prix de la BD INTER
	CE <?php echo $this->tAwards[0]->year ?>.</p>
<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
<p>Rendez-vous sur <a href="<?php echo $url ?>"><?php echo _root::getConfigVar('vfa-app.title') ?></a> pour valider son
	inscription.</p>
</body>
</html>
