<script>
	$(document).ready(function () {

		var htmlAucuneNote = "<h4><span class=\"label label-warning\">Aucune note</span></h4>";
		var htmlVotreNoteBegin = "<h4>Votre note : ";
		var htmlVotreNoteFin = "</h4>";

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

		function checkTextArea(p_Item) {
			var isOriginal = ($(p_Item).val() == $(p_Item).data("original"));
			checkToSave($(p_Item).attr("name"), isOriginal);
		}

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
				noteText.html(htmlAucuneNote);
			} else {
				selectNote.toggleClass("active");
				noteText.html(htmlVotreNoteBegin + note + htmlVotreNoteFin);
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
				if (note == "-1") {
					noteText.html(htmlAucuneNote);
					p_RootGroup.find(".btn-nonote").hide();
				} else {
					p_Note.addClass('active');
					noteText.html(htmlVotreNoteBegin + note + htmlVotreNoteFin);
					p_RootGroup.find(".btn-nonote").show();
				}
				var isOriginal = (note == inputHidden.data("original"));
				checkToSave(inputHidden.attr("name"), isOriginal);
			}
			//	debug(p_Note.data("select-note"));
			//	debug(p_RootGroup.find("[type=hidden]").prop("value"));
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


	});
</script>