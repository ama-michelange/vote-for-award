<?php if (false == _root::getAuth()->isConnected()): ?>
    <div id="modalLogin" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="<?php echo $this->getLink('default::index') ?>" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3 id="modalLoginLabel">S'identifier ...</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputIdent">Identifiant</label>
                            <input type="text" id="inputIdent" name="login" class="form-control"
                                   placeholder="Mon identifiant"
                                   required autofocus/>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Mot de passe</label>
                            <input type="password" id="inputPassword" name="password" class="form-control" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="actionLogin">
                            <i class="glyphicon glyphicon-ok with-text"></i>Ok
                        </button>
                        <button class="btn btn-default" data-dismiss="modal">
                            <i class="glyphicon glyphicon-remove with-text"></i>Annuler
                        </button>
                        <a class="pull-left" href="<?php echo $this->getLink('connection::forgotten') ?>"><i
                                class="glyphicon glyphicon-fire with-text"></i>J'ai oublié mon mot de passe !</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-bsnavbar">
                <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
            <a href="https://www.alice-et-clochette.fr/" target="aec">
                <img src="https://www.alice-et-clochette.fr/assets/img/ac.svg" class="navbar-brand navbar-logo">
            </a>
            <?php echo $this->oNavBar->toHtmlTitle(); ?>
        </div>
        <div class="navbar-collapse collapse navbar-bsnavbar">
            <?php echo $this->oNavBar->toHtml(); ?>
        </div>
    </div>
</div>

