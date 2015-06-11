<!-- Modal -->
<div id="modalReject" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Vous ne souhaitez pas vous inscrire ...</h3>
			</div>
			<div class="modal-body">
				<p>Vous ne pourrez donc pas voter !</p>

				<h1>Etes-vous sûr ?</h1>
			</div>
			<div class="modal-footer">
				<form id="toReject" action="<?php echo $this->getLink('autoreg::toReject') ?>" method="POST">
					<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
					<input type="hidden" name="invitation_key"
								 value="<?php echo $this->oConfirm->invitation_key ?>"/>
					<button class="btn btn-default btn-sm" type="submit">Oui</button>
					<button class="btn btn-info btn-lg" data-dismiss="modal">Non</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="well well-sm">
	<h1 class="text-center margin-bottom-max">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
</div>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oConfirm->titleInvit ?></h3>
	</div>
	<div class="panel-body">
		<h4 style="line-height: 1.7;"><?php echo $this->oConfirm->textInvit ?></h4>
		<h4>&nbsp;</h4>

		<form id="toConfirm" action="<?php echo $this->getLink('autoreg::toConfirm') ?>" method="POST">
			<input type="hidden" name="invitation_id" value="<?php echo $this->oConfirm->invitation_id ?>"/>
			<input type="hidden" name="invitation_key" value="<?php echo $this->oConfirm->invitation_key ?>"/>

			<h1 class="text-center">
				<!--				<i class="pull-left glyphicon glyphicon-hand-right with-text"></i>-->
				Souhaitez-vous vous inscrire ?
			</h1>

			<h1 class="text-center">
				<span
					class="nowrap">
					<button class="btn btn-info btn-lg" type="submit" name="action" value="toConfirm">Oui</button>
					<a class="btn btn-default btn-sm" href="#modalReject" data-toggle="modal">Non</a>
				</span>
			</h1>
		</form>
	</div>
</div>
