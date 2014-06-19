<script>
	$(document).ready(function () {
		$('#collapseAccount').on({
			'show.bs.collapse': function () {
				$('#panelLogin').hide();
				$('#panelPassword').hide();
			},
			'shown.bs.collapse': function () {
				$('#btnAccount').fadeOut(800);
			},
			'hidden.bs.collapse': function () {
				$('#btnAccount').fadeIn(800);
			},
			'hide.bs.collapse': function () {
				$('#panelLogin').fadeIn(800);
				$('#panelPassword').fadeIn(800);
			}
		});
		$('#cancelAccount').on('click', function () {
			$('#collapseAccount').collapse('hide');
		});

		$('#collapseLogin').on({
			'show.bs.collapse': function () {
				$('#panelAccount').hide();
				$('#panelPassword').hide();
			},
			'shown.bs.collapse': function () {
				$('#btnLogin').fadeOut(800);
			},
			'hidden.bs.collapse': function () {
			},
			'hide.bs.collapse': function () {
			}
		});
		$('#cancelLogin').on('click', function () {
			$('#panelAccount').show();
			$('#panelPassword').show();
			$('#collapseLogin').collapse('hide');
			$('#btnLogin').fadeIn(800);
		});

		$('#collapsePassword').on({
			'show.bs.collapse': function () {
				$('#panelAccount').hide();
				$('#panelLogin').hide();
			},
			'shown.bs.collapse': function () {
				$('#btnPassword').fadeOut(800);
			},
			'hidden.bs.collapse': function () {
//				$('#btnPassword').fadeIn(800);
			},
			'hide.bs.collapse': function () {
//				$('#panelAccount').fadeIn();
//				$('#panelLogin').fadeIn();
			}
		});
		$('#cancelPassword').on('click', function () {
			$('#panelAccount').show();
			$('#panelLogin').show();
			$('#collapsePassword').collapse('hide');
			$('#btnPassword').fadeIn(800);
		});
	});

</script>