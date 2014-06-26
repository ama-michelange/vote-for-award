<script>
	$(document).ready(function () {
		$('#collapseAccount').on({
			'show.bs.collapse': function () {
				$('#btnAccount').show();
				$('#btnLogin').show();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#btnAccount').hide();
			}
		});
		$('#cancelAccount').on('click', function () {
			$('#btnLogin').show();
			$('#btnPassword').show();
			$('#btnAccount').show();
			$('#collapseAccount').collapse('hide');
		});

		$('#collapseLogin').on({
			'show.bs.collapse': function () {
				$('#btnAccount').show();
				$('#btnLogin').show();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#btnLogin').hide();
			}
		});
		$('#cancelLogin').on('click', function () {
			$('#btnAccount').show();
			$('#btnPassword').show();
			$('#btnLogin').show();
			$('#collapseLogin').collapse('hide');
		});

		$('#collapsePassword').on({
			'show.bs.collapse': function () {
				$('#btnAccount').show();
				$('#btnLogin').show();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#btnPassword').hide();
			}
		});
		$('#cancelPassword').on('click', function () {
			$('#btnAccount').show();
			$('#btnLogin').show();
			$('#btnPassword').show();
			$('#collapsePassword').collapse('hide');
		});
	});

</script>