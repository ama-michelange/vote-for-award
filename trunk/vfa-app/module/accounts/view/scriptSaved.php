<div id="modalAccount" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Données enregistrées</h3>
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