<?php if ($this->errorLogin): ?>
<script>
	$(document).ready(function () {
//		$('#test').on('click', function (pEvent) {
//			pEvent.preventDefault();
			var item = $(".carousel-inner .item.active .text-center");
			item.addClass("error-identity");
			item.html('<i class="glyphicon glyphicon-minus-sign"></i>');
			var caption = $(".carousel-inner .item.active .carousel-caption");
			caption.html("<h3>Identifiant ou mot de passe invalide !</h3>");
//		});
	});
</script>
<?php endif; ?>