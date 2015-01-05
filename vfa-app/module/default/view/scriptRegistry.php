<script>
	$(document).ready(function () {
		$('#collapseAccount').on({
			'show.bs.collapse': function () {
				$('#btnAccount').hide();
				$('#btnLogin').show();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#bottomAccount').get(0).click();
				$('#inputLastName').focus();
			}
		});
		$('#cancelAccount').on('click', function (pEvent) {
			pEvent.preventDefault();
			$('#btnLogin').show();
			$('#btnPassword').show();
			$('#btnAccount').show();
			$('#collapseAccount').collapse('hide');
		});
		$('#bottomAccount').on('focus', function () {
			focusForm();
		});

		$('#collapseLogin').on({
			'show.bs.collapse': function () {
				$('#btnAccount').show();
				$('#btnLogin').hide();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#bottomLogin').get(0).click();
				$('#cfLogin').focus();
			}
		});
		$('#cancelLogin').on('click', function (pEvent) {
			pEvent.preventDefault();
			$('#btnAccount').show();
			$('#btnPassword').show();
			$('#btnLogin').show();
			$('#collapseLogin').collapse('hide');
		});
		$('#bottomLogin').on('focus', function () {
			focusForm();
		});

		$('#collapsePassword').on({
			'show.bs.collapse': function () {
				$('#btnAccount').show();
				$('#btnLogin').show();
				$('#btnPassword').hide();
			},
			'shown.bs.collapse': function () {
				$('#bottomPassword').get(0).click();
				$('#inputMyEmail').focus();
			}
		});
		$('#cancelPassword').on('click', function (pEvent) {
			pEvent.preventDefault();
			$('#btnAccount').show();
			$('#btnLogin').show();
			$('#btnPassword').show();
			$('#collapsePassword').collapse('hide');
		});
		$('#bottomPassword').on('focus', function () {
			focusForm();
		});

		$('#inputLastName').on('blur', function () {
			buildIdent();
		});
		$('#inputFirstName').on('blur', function () {
			buildIdent();
		});

		function buildIdent() {
			var valLastName = $('#inputLastName').val();
			var valFirstName = $('#inputFirstName').val();
			var login = $('#login');
			if (valFirstName && valLastName && !login.val()) {
				login.val(valFirstName.toLowerCase() + '.' + valLastName.toLowerCase());
			}
		}

		function focusForm() {
			var form = $('form:visible');
			var idForm = form.attr('id');
			if (idForm) {
				var error = form.find('.has-error:first');
				if (error.length) {
					var input = error.find(':input');
					input.focus();
				}
			}
		}
	});
</script>