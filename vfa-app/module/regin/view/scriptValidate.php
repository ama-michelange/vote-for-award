<script>
	$(document).ready(function () {
		$(".btn-valid-on").on({
			click: function () {
				pushButton($(this), true);
			}
		});

		$(".btn-valid-off").on({
			click: function () {
				pushButton($(this), false);
			}
		});

		function pushButton(p_Button, p_State) {
			var parent = p_Button.parents("tr");
			var id = p_Button.data("id");
			var vaglyd = parent.find(".vaglyd");
			var inputHidden = parent.find("input");
			var nameHidden = inputHidden.attr("name");

			parent.removeClass("info warning");
			vaglyd.removeClass("glyphicon-ok glyphicon-remove glyphicon-question-sign");

			switch (nameHidden) {
				case "accepted[]":
					if (true == p_State) {
						vaglyd.addClass("glyphicon-question-sign");
						inputHidden.attr("name", "unkknown[]");
					}
					else {
						parent.addClass("warning");
						vaglyd.addClass("glyphicon-remove");
						inputHidden.attr("name", "rejected[]");
					}
					break;
				case "rejected[]":
					if (true == p_State) {
						parent.addClass("info");
						vaglyd.addClass("glyphicon-ok");
						inputHidden.attr("name", "accepted[]");
					}
					else {
						vaglyd.addClass("glyphicon-question-sign");
						inputHidden.attr("name", "unkknown[]");
					}
					break;
				default :
					if (true == p_State) {
						parent.addClass("info");
						vaglyd.addClass("glyphicon-ok");
						inputHidden.attr("name", "accepted[]");
					}
					else {
						parent.addClass("warning");
						vaglyd.addClass("glyphicon-remove");
						inputHidden.attr("name", "rejected[]");
					}
					break;
			}

		}

		<?php if ($this->oRegin->openModalConfirm): ?>
		$('#modalConfirmValidate').modal({'show': true, 'keyboard': true, 'backdrop': 'static'});
		<?php endif; ?>

	});
</script>