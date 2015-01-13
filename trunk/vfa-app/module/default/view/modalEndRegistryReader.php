<div id="modalEnd" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form action="" method="POST">
				<input type="hidden" name="regin_id" value="<?php echo $this->oRegistry->regin_id ?>"/>
				<input type="hidden" name="user_id" value="<?php echo $this->oRegistry->oUser->user_id ?>"/>
				<div class="modal-header">
<!--					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
					<h4 class="modal-title">Inscription terminée</h4>
				</div>
				<div class="modal-body">
					<h4>Le texte dans le body</h4>
					<p>mlkjqdsf mqsldkjf qsmdlkjf azeiou trmqsiu fgsqlkd fgqlkjsdfh kj</p>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info" name="action" value="toConnect">
						<i class="glyphicon glyphicon-log-in with-text"></i>Connexion
					</button>
				</div>
			</form>
		</div>
	</div>
</div>