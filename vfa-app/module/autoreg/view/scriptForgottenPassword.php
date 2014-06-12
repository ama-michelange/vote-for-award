<?php if ($this->oConnection->openModalForgottenPassword): ?>
<script>
	$(document).ready(function () {
		$('#modalForgottenPassword').modal({'show':true, 'keyboard':true});
	});
</script>
<?php endif; ?>