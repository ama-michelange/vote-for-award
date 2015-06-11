<?php if ($this->oConnection->openModalMessage): ?>
	<div id="modalMessage" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h4><?php echo $this->oConnection->textModalMessage ?></h4>
				</div>
				<div class="modal-footer">
					<?php if ($this->oConnection->linkWhenClose): ?>
						<a class="btn btn-primary" href="<?php echo _root::getLink($this->oConnection->linkWhenClose) ?>">
							<i class="glyphicon glyphicon-remove with-text"></i>Fermer
						</a>
					<?php else: ?>
						<button class="btn btn-primary" data-dismiss="modal">
							<i class="glyphicon glyphicon-remove with-text"></i>Fermer
						</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
