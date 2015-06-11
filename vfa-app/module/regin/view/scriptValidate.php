<script>
	$(document).ready(function () {
		$(".btn-all-valid-on").on({
			click: function () {
				pushAllButtons(1);
			}
		});

		$(".btn-all-valid-question").on({
			click: function () {
				pushAllButtons(0);
			}
		});

		$(".btn-all-valid-off").on({
			click: function () {
				pushAllButtons(-1);
			}
		});

		$(".btn-valid-on").on({
			click: function () {
				pushButton($(this), 1);
			}
		});

		$(".btn-valid-question").on({
			click: function () {
				pushButton($(this), 0);
			}
		});

		$(".btn-valid-off").on({
			click: function () {
				pushButton($(this), -1);
			}
		});

		function pushAllButtons(p_State) {
			var parents = $("[id^=tr_]");
			parents.each(function () {
				var button = $(this).find(".btn-valid-on");
				pushButton(button, p_State);
			});

		}

		function pushButton(p_Button, p_State) {
			var parent = p_Button.parents("tr");
			var id = p_Button.data("id");
			var vaglyd = parent.find(".vaglyd");
			var inputHidden = parent.find("input");

			parent.removeClass("info warning");
			vaglyd.removeClass("glyphicon-ok glyphicon-remove glyphicon-question-sign");

			switch (p_State) {
				case 1:
					parent.addClass("info");
					vaglyd.addClass("glyphicon-ok");
					inputHidden.attr("name", "accepted[]");
					break;
				case 0:
					vaglyd.addClass("glyphicon-question-sign");
					inputHidden.attr("name", "unkknown[]");
					break;
				case -1 :
					parent.addClass("warning");
					vaglyd.addClass("glyphicon-remove");
					inputHidden.attr("name", "rejected[]");
					break;
			}
		}

		<?php if ($this->oRegin->openModalConfirm): ?>
		$('#modalConfirmValidate').modal({'show': true, 'keyboard': true, 'backdrop': 'static'});
		<?php endif; ?>

	});
</script>
