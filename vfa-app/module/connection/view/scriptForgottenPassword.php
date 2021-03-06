<?php if ($this->oConnection->openModalForgottenPassword): ?>
	<script>
		$(document).ready(function () {
			$('#modalForgottenPassword').modal({'show': true, 'keyboard': true});
		});
	</script>
<?php endif; ?>
<?php if ($this->oConnection->openModalMessage): ?>
	<script>
		$(document).ready(function () {
			$('#modalMessage').modal({'show': true, 'keyboard': true});
			<?php if ($this->oConnection->redirectOnClose): ?>
			$('#modalMessage').on('hide.bs.modal', function (e) {
				window.location.replace("<?php echo _root::getLinkString('default::index') ?>");
			});
			<?php endif; ?>
		});
	</script>
<?php endif; ?>

<script>
	$(document).ready(function () {
		$('#toForgottenPassword').submit(function () {
			$('#submitForgottenPassword').prop('disabled', 'disabled');
		});
	});
</script>
