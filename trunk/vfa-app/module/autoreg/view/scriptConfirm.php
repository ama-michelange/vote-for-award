<script>
	$(document).ready(function () {
		$('#collapseAccount').on({
			'shown.bs.collapse': function () {
				$('#btnAccount').hide();
				$('#bottomAccount').get(0).click();
			},
			'hide.bs.collapse': function () {
				$('#btnAccount').show();
			}
		});
		$('#cancelAccount').on('click', function () {
			$('#collapseAccount').collapse('hide');
		});
		$('#collapseLogin').on({
			'shown.bs.collapse': function () {
				$('#btnLogin').hide();
				$('#bottomLogin').get(0).click();
			},
			'hidden.bs.collapse': function () {
				$('#btnLogin').show();
			}
		});
		$('#cancelLogin').on('click', function () {
			$('#collapseLogin').collapse('hide');
		});
	});


</script>