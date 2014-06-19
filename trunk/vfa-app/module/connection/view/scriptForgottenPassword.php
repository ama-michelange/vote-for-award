<?php if ($this->oConnection->openModalForgottenPassword): ?>
<script>
	$(document).ready(function () {
		$('#modalForgottenPassword').modal({'show':true, 'keyboard':true});
	});
</script>
<?php endif; ?>
<?php if ($this->oConnection->openModalMessage): ?>
	<script>
		$(document).ready(function () {
			$('#modalMessage').modal({'show':true, 'keyboard':true});
		});
	</script>
<?php endif; ?>