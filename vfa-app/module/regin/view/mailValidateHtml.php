<html><body>
<p>Votre inscription au Prix de la BD INTER CE <?php echo $this->tAwards[0]->year ?> est validée.</p>
<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base'); ?>
<p>Rendez-vous sur <a href="<?php echo $url ?>"><?php echo _root::getConfigVar('vfa-app.title') ?></a> pour voter.</p>
</body></html>