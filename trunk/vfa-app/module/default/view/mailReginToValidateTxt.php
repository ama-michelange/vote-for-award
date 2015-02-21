L'utilisateur <?php echo $this->oUser->toStringPublic() ?> vient de s'inscrire au Prix de la BD INTER CE <?php echo $this->tAwards[0]->year ?>.

<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
Pour valider son inscription, rendez-vous sur <?php echo $url ?>
