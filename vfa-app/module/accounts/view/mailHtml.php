<html>
<body>
<p>Vous souhaitez changer l'adresse Email de votre compte sur <?php echo _root::getConfigVar('vfa-app.title') ?>.</p>

<p>Rendez-vous à l'adresse suivante pour valider le changememt :
	<a
		href="<?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?>"><?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?></a>
</p>

<p>Merci</p>
</body>
</html>
