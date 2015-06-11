<html>
<body>
<p>Vous avez oublié votre mot de passe d'accès à votre compte sur <?php echo _root::getConfigVar('vfa-app.title') ?>
	.</p>

<p>Rendez-vous à l'adresse suivante pour saisir un nouveau mot de passe :
	<a
		href="<?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?>"><?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?></a>
</p>

<p>Merci</p>
</body>
</html>
