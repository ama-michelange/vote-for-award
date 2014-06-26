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
				$('#btnLogin').hide();
				$('#btnPassword').show();
			},
			'shown.bs.collapse': function () {
				$('#bottomLogin').get(0).click();
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
				$('#btnPassword').hide();
			},
			'shown.bs.collapse': function () {
				$('#bottomPassword').get(0).click();
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