<div id="modalAccount" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<button type="button" class="close" data-dismiss="modal">×</button>

				<h4><i class="glyphicon glyphicon-info-sign text-primary with-text"></i>
					<?php if ($this->title ) { echo $this->title; } ?>
				</h4>
			</div>
			<div class="modal-body">
<!--				<button type="button" class="close" data-dismiss="modal">×</button>-->
				<h4>
<!--					<i class="glyphicon glyphicon-info-sign with-text"></i>-->
					<?php echo $this->text ?></h4>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" data-dismiss="modal">
					<i class="glyphicon glyphicon-remove with-text"></i>Fermer
				</button>
			</div>
		</div>
	</div>
</div>

<script>

$(document).ready(function () {
	$('#modalAccount').modal({'show':true, 'keyboard':true});
});

</script>