L'utilisateur <?php echo $this->oUser->toStringPublic() ?> vient de s'inscrire au Prix de la BD INTER CE <?php echo $this->tAwards[0]->year ?>.

<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
Rendez-vous sur <a href="<?php echo $url ?>"><?php echo $url ?></a> pour valider son inscription.


