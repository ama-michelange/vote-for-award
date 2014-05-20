<?php
_root::setConfigVar('tLangue',
	array(

		'BONJOUR' => 'Bonjour',
		'BIENVENUE' => 'Bienvenue',
		'CHOISISSEZ_LANGUE' => 'Choisissez la langue',
		'DU_TEXTE_EN_FRANCAIS' => 'Du texte en francais',
		'La_date' => 'La date',

		// Rôles
		'role.board' => 'Membre du comité de sélection',
		'role.bookseller' => 'Libraire',
		'role.organizer' => 'Organisateur',
		'role.owner' => 'Propriétaire',
		'role.reader' => 'Lecteur',
		'role.responsible' => 'Correspondant',

		// Groupes
		'group.board' => 'Comité de sélection',
		'group.bookseller' => 'Libraires',
		'group.reader' => 'Lecteurs',

		'title.users' => 'Comptes',
		'title.users.listBoardGroup' => 'Comité de sélection',
		'title.users.listDetailed' => 'Comptes détaillés',
		'title.users.listResponsibleGroup' => 'Correspondants',

		'menu.users' => 'Comptes',

		// Messages d'erreurs
		'token' => '<strong>Page invalide !</strong><p>Une page de saisie n\'est valable qu\'un certain temps qui est dépassé.</p>',
		'isNotEmptyKO' => 'Saisie obligatoire !',
		'isNotEmptyOrKO' => 'Saisie obligatoire !',
		'isEqualKO' => 'La valeur saisie est différente de la valeur attendue',
		'isEmailValidKO' => 'Cette adresse email est invalide',
		'matchExpressionKO.number' => "La valeur saisie n'est pas un nombre",
		'isDateBeforeKO' => 'Cohérence des dates : date trop grande !',
		'isDateAfterKO' => 'Cohérence des dates : date trop petite !',
		'isNotContainsAtKO' => 'La valeur saisie contient un caractère interdit !',

		'doublon' => 'Cet identifiant est déjà utilisé !',
		'NotEmpty.newLogin' => 'Un identifiant ne peut pas être vide !',
		'matchExpressionKO.birthyear' => "La valeur saisie doit correspondre à une année valide (nombre)",
		'isUpperOrEqualThanKO.birthyear' => "Pas possible, c'est trop vieux !",
		'isLowerThanKO.birthyear' => "Pas possible, c'est trop jeune !",
		'isEqualKO.email' => 'Les emails ne correspondent pas !',
		'isEqualKO.email_bis' => 'Les emails ne correspondent pas !',
		'isEqualKO.newPassword' => 'Le mot de passe et sa confirmation ne correspondent pas !',
		'isEqualKO.confirmPassword' => 'Le mot de passe et sa confirmation ne correspondent pas !',
		'badSize.newPassword' => 'Le mot de passe doit contenir entre 7 et 30 caractères !',
		'notUniqueTypeGroup' => 'Le type des groupes n\'est pas unique !',
		'invalidGroupsWithRoles' => 'Les groupes sont invalides par rapport aux rôles !',
		'invalidRolesWithGroups' => 'Les rôles sont invalides par rapport aux groupes !',


		'matchExpressionKO.year' => "La valeur saisie doit correspondre à une année valide (nombre)",
		'isUpperOrEqualThanKO.year' => "L'année du prix doit appartenir au XXI siècle",
		'doublon.year' => 'Ce prix existe déjà !',
		'doublon.name' => 'Ce prix existe déjà !',
		'doublon.type' => 'Ce prix existe déjà !',

		'required-selection' => "Sélection obligatoire !",
		'required-selection.title_docs' => "Sélectionnez au minimum un album !",
		'all-equals.title_docs' => "Les albums sélectionnés doivent être de la même série !",
		'isUnique.title_docs' => "La combinaison des albums sélectionnés existe déjà !",
		'none.awards' => 'Vous n\'êtes inscris à aucun prix !',
		'nonePBD.awards' => 'Aucun prix trouvé !',
		'nonePSBD.awards' => 'Aucune présélection trouvée !',
		'none.groups' => 'Vous n\'appartenez à aucun groupe !',
		'noneCS.groups' => 'Aucun groupe de comité de sélection trouvé !'
	));


