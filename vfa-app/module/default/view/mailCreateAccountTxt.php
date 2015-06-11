Bonjour <?php echo $this->oUser->toStringFirstLastName() . ",\n" ?>

Vous venez de créer un compte sur <?php echo _root::getConfigVar('vfa-app.title') ?>.

Votre identifiant de connexion est : <?php echo $this->oUser->login . "\n" ?>

