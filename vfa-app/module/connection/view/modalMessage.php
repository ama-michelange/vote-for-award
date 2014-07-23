<?php if ($this->oConnection->openModalMessage): ?>
	<div id="modalMessage" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h4><?php echo $this->oConnection->textModalMessage ?></h4>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">
						<i class="glyphicon glyphicon-remove with-text"></i>Fermer
					</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>