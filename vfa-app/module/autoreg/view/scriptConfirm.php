<script>
	$(document).ready(function () {
		$('#collapseAccount').on({
			'shown.bs.collapse': function () {
				$('#panelLogin').hide();
				$('#btnAccount').hide();
				$('#bottomAccount').get(0).click();
			},
			'hide.bs.collapse': function () {
				$('#panelLogin').show();
				$('#btnAccount').show();
			}
		});
		$('#cancelAccount').on('click', function () {
			$('#collapseAccount').collapse('hide');
		});
		$('#collapseLogin').on({
			'show.bs.collapse': function () {
				$('#panelAccount').hide();
				$('#btnLogin').hide();
				$('#bottomLogin').get(0).click();
			},
			'hide.bs.collapse': function () {
				$('#panelAccount').show();
				$('#btnLogin').show();
			}
		});
		$('#cancelLogin').on('click', function () {
			$('#collapseLogin').collapse('hide');
		});
	});


</script>