<?php
/*
       * <!-- Modal --> <div id="modalReject" class="modal fade"> <div class="modal-dialog"> <div class="modal-content"> <form class="form-horizontal" action="" method="POST"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">×</button> <h3>Vous souhaitez refuser l'inscription ...</h3> </div> <div class="modal-body"> <h1>Etes-vous sûr ?</h1> </div> <div class="modal-footer"> <a class="btn btn-primary btn-sm" href="<?php echo $this->getLink('autoreg::toReject',array('id'=>_root::getParam('id'),'key'=>_root::getParam('key'))) ?>">Oui</a> <button class="btn btn-default btn-lg" data-dismiss="modal">Non</button> </div> </form> </div> </div> </div>
       */
?>
<div class="well well-sm">
	<h1 class="text-center"><?php echo _root::getConfigVar('vfa-app.title') ?></h1>

	<h3 class="text-center">Site de vote du Prix de la Bande Dessinée</h3>
</div>
<?php if(plugin_validation::exist($this->tMessage, 'token')):?>
<div class="alert alert-warning clearfix">
	<p><?php echo plugin_validation::show($this->tMessage, 'token')?>
		<a class="btn btn-sm btn-warning pull-right"
			href="<?php echo $this->getLink('autoreg::index',array('id'=>$this->oConfirm->invitation_id ,'key'=>$this->oConfirm->invitation_key)) ?>">Fermer</a>
	</p>
</div>
<?php else:?>
	<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oConfirm->titleInvit ?></h3>
	</div>
	<div class="panel-body">
		<!--		<div class="row">-->
		<!--			<div class="col-sm-6 col-md-6">-->
		<!--				<div class="panel panel-default panel-inner">-->
		<!--					<div class="panel-body">-->
		<?php foreach ($this->oConfirm->tInscription as $label => $value): ?>
			<div class="row">
				<div class="col-sm-3 col-md-3 col-lg-2 view-label"><?php echo $label ?></div>
				<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $value ?></div>
			</div>
		<?php endforeach; ?>
		<!--					</div>-->
		<!--				</div>-->
		<!--			</div>-->
		<!--		</div>-->
	</div>
</div>
	<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Validation de votre inscription</h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel-group" id="accordion">
			<div class="panel panel-info">
				<div class="panel-body panel-inner">
					<div class="row">
						<div class="col-sm-1">
							<h1 class="text-center text-info"><i class="glyphicon glyphicon-star-empty"></i></h1>
						</div>
						<div class="col-sm-11">
							<h3>
								Vous n'avez pas de compte sur ce site :
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseLogin" class="btn btn-info"><i
										class="glyphicon glyphicon-record with-text"></i>Enregistrez-vous !</a>
							</h3>
						</div>
					</div>
				</div>
				<div id="collapseLogin" class="panel-collapse collapse">
					<div class="panel-body panel-inner">
						<?php echo $this->oViewFormAccount->show(); ?>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-body panel-inner">
					<div class="row">
						<div class="col-sm-1">
							<h1 class="text-center text-info"><i class="glyphicon glyphicon-star"></i></h1>
						</div>
						<div class="col-sm-11">
							<h3>
								Vous avez déjà voté sur ce site et vous avez un compte :
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseAccount" class="btn btn-info"><i
										class="glyphicon glyphicon-user with-text"></i>Identifiez-vous !</a>
							</h3>
						</div>
					</div>
				</div>
				<div id="collapseAccount" class="panel-collapse collapse">
					<div class="panel-body panel-inner">
						<?php echo $this->oViewFormIdent->show(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;?>
