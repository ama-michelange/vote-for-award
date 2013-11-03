<!-- Modal -->
<div id="modalReject" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>Vous ne souhaitez pas vous inscrire ...</h3>
				</div>
				<div class="modal-body">
					<p>Vous ne pourrez donc pas voter !</p>
					<h1>Etes-vous sûr ?</h1>
				</div>
				<div class="modal-footer">
					<a class="btn btn-primary btn-sm" href="<?php echo $this->getLink('autoreg::toReject',array('id'=>_root::getParam('id'),'key'=>_root::getParam('key'))) ?>">Oui</a>
					<button class="btn btn-default btn-lg" data-dismiss="modal">Non</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="well well-sm">
	<h1 class="text-center">Bienvenue sur ALICES Award</h1>
	<h3 class="text-center">Site de vote du Prix de la Bande Dessinée</h3>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Invitation pour participer au Prix BD</h3>
	</div>
	<div class="panel-body">
		<h3><?php echo $this->oConfirm->textInvit ?></h3>
		<h5>Cette inscription vous permettra de voter sur ce site.</h5>
		<h1>Souhaitez-vous vous inscrire ?
			<a class="btn btn-primary btn-lg" href="">Oui</a>
			<a class="btn btn-default btn-sm" href="#modalReject" data-toggle="modal">Non</a>
		</h1>
	</div>
</div>
