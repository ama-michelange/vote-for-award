<p>Bonjour,</p>
<p><?php echo plugin_vfa::buildTextInvitation($this->oInvit, true) ?></p>
<p>
	Rendez-vous à l'adresse suivante pour valider :
	<a
		href="<?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?>"><?php echo plugin_vfa::generateURLInvitation($this->oInvit) ?></a>
</p>
<p>Merci</p>
