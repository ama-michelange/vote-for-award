<div class="well well-sm">
    <p class="aec text-center">
        <a href="https://www.alice-et-clochette.fr/" target="aec">
            <img id="aec" src="https://www.alice-et-clochette.fr/assets/img/aec-white.svg" alt="Logo Alice et Clochette"></a>
    </p>
    <h1 class="text-center">
        <span class="text-nowrap">Bureau de votes</span>
    </h1>
</div>
<div class="row">
    <?php if (count($this->toReaderRegins) > 0) : ?>
        <div class="col-md-8">
            <div class="panel panel-success">
                <div class="panel-heading"><h3 class="panel-title">Inscription en attente de validation</h3></div>
                <div class="panel-body panel-condensed">
                    <h4>Le correspondant doit d'abord valider votre inscription au prix ci-dessous</h4>
                    <ul class="list-group">
                        <?php foreach ($this->toReaderRegins as $oRegin) : ?>
                            <?php $toAwardRegs = $oRegin->findAwards(); ?>
                            <?php foreach ($toAwardRegs as $oAward) : ?>
                                <li class="list-group-item">
                                    <?php echo($oAward->toString()) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h4>Ensuite, vous pourrez voter.</h4>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($this->toBoardRegins) > 0) : ?>
        <div class="col-md-8">
            <div class="panel panel-success">
                <div class="panel-heading"><h3 class="panel-title">Inscription en attente de validation</h3></div>
                <div class="panel-body panel-condensed">
                    <h4>L'organisateur doit d'abord valider votre inscription à la présélection ci-dessous</h4>
                    <ul class="list-group">
                        <?php foreach ($this->toBoardRegins as $oRegin) : ?>
                            <?php $toAwardRegs = $oRegin->findAwards(); ?>
                            <?php foreach ($toAwardRegs as $oAward) : ?>
                                <li class="list-group-item">
                                    <?php echo($oAward->toString()) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h4>Ensuite, vous pourrez voter.</h4>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->reginToValidate) : ?>
        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading"><h3 class="panel-title">Valider</h3></div>
                <div class="panel-body panel-condensed">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="<?php echo $this->getLink('regin::validate') ?>">
                                Inscriptions à valider</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($this->toUserRegistredAwards) > 0) : ?>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Voter</h3></div>
                <div class="panel-body panel-condensed">
                    <ul class="list-group">
                        <?php foreach ($this->toUserRegistredAwards as $oAward) : ?>
                            <li class="list-group-item">
                                <a href="<?php echo $this->getLink('votes::index', array('award_id' => $oAward->getId())) ?>">
                                    <?php echo($oAward->toString()) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($this->toInProgressAwards) > 0) : ?>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Voir</h3></div>
                <div class="panel-body panel-condensed">
                    <ul class="list-group">
                         <?php foreach ($this->toInProgressAwards as $oAward) : ?>
                            <li class="list-group-item">
                                <a href="<?php echo $this->getLink('results::awardInProgress', array('award_id' => $oAward->getId())) ?>">
                                    Sélection <?php echo($oAward->toStringName()) ?></a>
                            </li>
                         <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($this->tHelp) > 0) : ?>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Aide</h3></div>
                <div class="panel-body panel-condensed">
                    <ul class="list-group">
                        <?php foreach ($this->tHelp as $help) : ?>
                            <li class="list-group-item">
                                <a href="<?php echo $this->getLink($help[1]) ?>">
                                    <?php echo($help[0]) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<div id="connected">
    <p class="text-center">avec</p>

    <div class="center-block pub-block">
        <div class="clearfix pub-images">
            <p class="pull-right">
                <a href="https://www.facebook.com/bdfuguegrenoble" target="partner2">
                    <img id="bdfugue" src="site/img/LogoBdFugueGrenoble.jpg"
                         alt="Logo BD Fugue Grenoble"></a></p>
            <p class="pull-right">
                <a href="http://www.lanouvellederive.com/" target="partner2">
                    <img id="lanouvellederive" src="site/img/logo_la-nouvelle-derive-vote.jpg"></a></p>
        </div>
    </div>
</div>
