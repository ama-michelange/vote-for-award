<script>
$(document).ready(function () {


	$("#myBrand").data("toSave", []);

	$(".btn-note").click(function () {
		if (false == $(this).hasClass('active')) {
			getNoteGroup($(this).parents("[data-notes-group]"), $(this));
		}
	});
	$(".btn-nonote").click(function () {
		if (false == $(this).hasClass('active')) {
			getNoteGroup($(this).parents("[data-notes-group]"), $(this));
		}
	});

	$("[data-notes-group]").each(function () {
		initNotesGroup($(this));
	});

	$("textarea").change(function () {
		var isOriginal = ($(this).val() == $(this).data("original"));
		checkToSave($(this).attr("name"), isOriginal);
	});

	$("textarea").keyup(function () {
		var $this = $(this);
		setTimeout(function () {
			var isOriginal = ($this.val() == $this.data("original"));
			checkToSave($this.attr("name"), isOriginal);
		}, 500);
	});

	function initNotesGroup(p_RootGroup) {
		//	debug(p_RootGroup.attr("data-notes-group"));
		var inputHidden = p_RootGroup.find("[type=hidden]");
		var note = inputHidden.val();
		inputHidden.data("save", note);
		inputHidden.data("original", note);
		var noteText = p_RootGroup.find("[data-note]");
		var finder = "[data-select-note=" + note + "]";
		var selectNote = p_RootGroup.find(finder);
		if (selectNote.length == 0) {
			p_RootGroup.find(".btn-nonote").hide();
			noteText.html(buildTextNote(note, true));
		} else {
			selectNote.toggleClass("active");
			noteText.html(buildTextNote(note, true));
		}
		var comment = p_RootGroup.find("textarea");
		comment.data("original", comment.val());
	}


	function getNoteGroup(p_RootGroup, p_Note) {
		//	debug(p_RootGroup.attr("data-notes-group"));
		desactiveGroup(p_RootGroup);
		var inputHidden = p_RootGroup.find("[type=hidden]");
		var noteText = p_RootGroup.find("[data-note]");

		var note = "-1";
		if (p_Note.hasClass("btn-note")) {
			note = p_Note.data("select-note");
		}
		// détecte le changement de valeur
		if (note != inputHidden.data("save")) {
			//	debug("Change : " + inputHidden.data("save") + " -> " + note);
			inputHidden.val(note);
			inputHidden.data("save", note);
			var isOriginal = (note == inputHidden.data("original"));
			if (note == "-1") {
				p_RootGroup.find(".btn-nonote").hide();
				noteText.html(buildTextNote(note, isOriginal));
			} else {
				p_Note.addClass('active');
				p_RootGroup.find(".btn-nonote").show();
				noteText.html(buildTextNote(note, isOriginal));
			}
		}
		checkToSave(inputHidden.attr("name"), isOriginal);

		//	debug(p_Note.data("select-note"));
		//	debug(p_RootGroup.find("[type=hidden]").prop("value"));
	}


	function buildTextNote(p_Note, p_Original) {
		var html;
		var label;
		var comment;
		var color;
		if (p_Note == "-1") {
			if (p_Original) {
				label = "warning";
			}
			else {
				label = "danger";
			}
			html = "<h4><span class=\"label label-" + label + "\">Non lu, aucun vote</span></h4>";
		}
		else {
			if (p_Original) {
				label = "primary";
				color = "#FFFFFF";
			} else {
				label = "warning";
				color = "#000000";
			}
			switch (p_Note) {
				case 0 :
				case "0":
					comment = "Sans intérêt";
					break;
				case 1 :
				case "1":
					comment = "Décevant";
					break;
				case 2 :
				case "2" :
					comment = "Moyen";
					break;
				case 3 :
				case "3" :
					comment = "Bon";
					break;
				case 4 :
				case "4" :
					comment = "Très bon";
					break;
				case 5 :
				case "5" :
					comment = "Excellent";
					break;
			}
			html = "<h4><span class='label label-" + label + "' style='color:#000000'>Vote <span class='label label-default'>" + p_Note + "</span>" +
				" <span class='small' style='color:" + color + "'>" + comment + "</span></span></h4>";
		}
		return html;
	}

	function desactiveGroup(p_RootGroup) {
		p_RootGroup.find("[data-select-note]").each(function () {
			$(this).removeClass('active');
		});
		p_RootGroup.find(".btn-nonote").removeClass("active");
	}


	function checkToSave(pName, pIsOriginal) {
		// debug(pName + " " + pIsOriginal);
		var brand = $("#myBrand");
		var toSave = brand.data("toSave");
		// debug("A : " + toSave);
		if (pIsOriginal) {
			// Suppression
			toSave.splice($.inArray(pName, toSave), 1);
		}
		else if (-1 == $.inArray(pName, toSave)) {
			// Ajout si pas déjà présent
			toSave.push(pName);
		}
		// debug("B : " + toSave);
		// debug("C : " + brand.data("toSave"));
		if (toSave.length > 0) {
			showButtonSave();
		} else {
			hideButtonSave();
		}
	}

	function showButtonSave() {
		var brand = $("#myBrand");
		if (brand.hasClass("navbar-brand")) {
			brand.text("Enregistrer votre bulletin").removeClass().addClass("btn btn-warning navbar-btn");
			brand.click(function (pEvent) {
				if (brand.data("toSave").length > 0) {
					pEvent.preventDefault();
					$("form").submit();
				}
			});
		}
	}

	function hideButtonSave() {
		var brand = $("#myBrand");
		if (brand.hasClass("navbar-btn")) {
			brand.text("Bulletin").removeClass().addClass("navbar-brand");
		}
	}

	// Private function for debugging.
	function debug(mess) {
		if (window.console && window.console.log) {
			window.console.log(mess);
		}
	}


})
;
</script>