<?php
/*
 *
 */

/**
 *
 * @author AMA
 */
class plugin_vfa
{

	const INVITATION_TYPE_BOARD = 'BOARD';

	const INVITATION_TYPE_READER = 'READER';

	const INVITATION_TYPE_RESPONSIBLE = 'RESPONSIBLE';

	const INVITATION_STATE_OPEN = 'OPEN';

	const INVITATION_STATE_SENT = 'SENT';

	const INVITATION_STATE_ACCEPTED = 'ACCEPTED';

	const INVITATION_STATE_REJECTED = 'REJECTED';

	const GROUP_TYPE_BOARD = 'BOARD';

	const GROUP_TYPE_READER = 'READER';

	/**
	 * Déplace, s'il existe, l'article du début d'un titre à la fin du même titre.
	 * Ex : 'La page blanche' devient 'Page blanche (La)'
	 *
	 * @param string $pTitle
	 *        	Titre à traiter
	 * @return string Le titre traité
	 */
	public static function pushArticle($pTitle)
	{
		$sTitleUp = trim(strtoupper($pTitle));
		$sTrimTitle = trim($pTitle);
		$sTitleRet = $sTrimTitle;
		
		if (false == empty($sTitleUp)) {
			if (0 == substr_compare($sTitleUp, 'LES ', 0, 4)) {
				$sTitleRet = ucfirst(substr($sTrimTitle, 4)) . ' (' . substr($sTrimTitle, 0, 3) . ')';
			} elseif (0 == substr_compare($sTitleUp, 'LE ', 0, 3)) {
				$sTitleRet = ucfirst(substr($sTrimTitle, 3)) . ' (' . substr($sTrimTitle, 0, 2) . ')';
			} elseif (0 == substr_compare($sTitleUp, 'LA ', 0, 3)) {
				$sTitleRet = ucfirst(substr($sTrimTitle, 3)) . ' (' . substr($sTrimTitle, 0, 2) . ')';
			} elseif ((0 == substr_compare($sTitleUp, 'L&#039;', 0, 7)) && (strlen($sTitleUp) > 7)) {
				$sTitleRet = ucfirst(substr($sTrimTitle, 7)) . ' (' . substr($sTrimTitle, 0, 7) . ')';
			}
		}
		return $sTitleRet;
	}

	/**
	 * Renvoie le premier paramètre.
	 * Le séparateur est celui de la QueryString '&'.
	 *
	 * @param string $pLink        	
	 * @return string Le 1er param sinon FALSE
	 */
	public static function getFirstParam($pLink)
	{
		$tParams = explode('&', $pLink);
		if (is_array($tParams)) {
			return $tParams[0];
		}
		return $tParams;
	}

	/**
	 * Renvoie le premier module du lien donné.
	 * Le séparateur est '::'.
	 *
	 * @param string $pLink        	
	 * @return string Le 1er module sinon FALSE
	 */
	public static function getFirstModule($pLink)
	{
		$tParams = explode('::', $pLink);
		if (is_array($tParams)) {
			return $tParams[0];
		}
		return $tParams;
	}

	/**
	 * Renvoie vrai si le lien partiel donné contient le ParamNav courant.
	 *
	 * @param string $pLink        	
	 * @return boolean
	 */
	public static function hasParamNav($pLink)
	{
		if (_root::getParamNav() == self::getFirstParam($pLink)) {
			return true;
		}
		return false;
	}

	/**
	 * Renvoie vrai si le lien partiel donné contient le Module courant.
	 *
	 * @param string $pLink        	
	 * @return boolean
	 */
	public static function hasModule($pLink)
	{
		if (_root::getModule() == self::getFirstModule(self::getFirstParam($pLink))) {
			return true;
		}
		return false;
	}

	/**
	 * Renvoie vrai si le lien partiel donné contient le Module courant.
	 *
	 * @param string $pLink        	
	 * @return boolean
	 */
	public static function hasModuleInLinks($pLinks)
	{
		foreach ($pLinks as $sLibelle => $item) {
			if (self::hasModule($item)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Charge le fichier I18N du répertoire 'data/i18n'
	 * défini par la propriété 'language.default' du fichier de configuration.
	 */
	public static function loadI18n()
	{
		if (! isset($_SESSION['lang'])) {
			$_SESSION['lang'] = _root::getConfigVar('language.default');
		}
		plugin_i18n::load($_SESSION['lang']);
	}

	/**
	 * Transforme une chaine au format SGBD en date.
	 *
	 * @param string $pDate
	 *        	La date au format SGBD
	 * @return plugin_date La date ou NULL si pb
	 */
	public static function toDateFromSgbd($pDate)
	{
		$oDate = null;
		if (isset($pDate)) {
			$oDate = new plugin_date($pDate, 'Y-m-d');
		}
		return $oDate;
	}

	/**
	 * Change le format d'une date.
	 *
	 * @param string $pDate
	 *        	La date au format source
	 * @param string $pFormatSrc
	 *        	Le format source
	 * @param string $pFormatDst
	 *        	Le format de destination
	 * @return string La date au format de destination ou NULL si pb
	 */
	public static function changeDateFormat($pDate, $pFormatSrc, $pFormatDst)
	{
		$newDate = null;
		if (isset($pDate)) {
			$oDate = new plugin_date($pDate, $pFormatSrc);
			if ($oDate->isValid()) {
				$newDate = $oDate->toString($pFormatDst);
			}
		}
		return $newDate;
	}

	/**
	 * Change le format d'une horodate.
	 *
	 * @param string $pDatetime
	 *        	La horodate au format source
	 * @param string $pFormatSrc
	 *        	Le format source
	 * @param string $pFormatDst
	 *        	Le format de destination
	 * @return string La horodate au format de destination ou NULL si pb
	 */
	public static function changeDatetimeFormat($pDate, $pFormatSrc, $pFormatDst)
	{
		$newDate = null;
		if (isset($pDate)) {
			$oDate = new plugin_datetime($pDate, $pFormatSrc);
			if ($oDate->isValid()) {
				$newDate = $oDate->toString($pFormatDst);
			}
		}
		return $newDate;
	}

	/**
	 * Change le format d'une date SGBD vers le format d'affichage.
	 *
	 * @param string $pDate
	 *        	La date au format SGBD
	 * @return La date au format d'affichage
	 */
	public static function toStringDateShow($pDate)
	{
		return self::changeDateFormat($pDate, 'Y-m-d', 'd/m/Y');
	}

	/**
	 * Change le format d'une date et d'une heure SGBD vers le format d'affichage.
	 *
	 * @param string $pDateTime
	 *        	La date et heure au format SGBD
	 * @return La date et l'haure au format d'affichage
	 */
	public static function toStringDateTimeShow($pDate)
	{
		return self::changeDatetimeFormat($pDate, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
	}

	/**
	 * Change le format d'une date d'affichage vers le format SGBD.
	 *
	 * @param string $pDate
	 *        	La date au format d'affichage
	 * @return La date au format SGBD
	 */
	public static function toStringDateSgbd($pDate)
	{
		return self::changeDateFormat($pDate, 'd/m/Y', 'Y-m-d');
	}

	/**
	 * La date et heure courante au format du SGBD.
	 *
	 * @return La date et heure au format SGBD
	 */
	public static function dateTimeSgbd()
	{
		return date('Y-m-d H:i:s');
	}

	/**
	 * Copie les valeurs du tableau dans les clés.
	 *
	 * @param array $pArray        	
	 * @return array
	 */
	public static function copyValuesToKeys($pArray)
	{
		$tRet = null;
		if (isset($pArray)) {
			$tRet = array();
			$size = count($pArray);
			for ($i = 0; $i < $size; ++ $i) {
				$tRet[$pArray[$i]] = $pArray[$i];
			}
		}
		return $tRet;
	}

	/**
	 * Formate le nom d'un titre avec le 1er enregistrement des documents.
	 *
	 * @param
	 *        	array of row_doc $ptRowDocs Tableau d'enregistrement de document
	 * @return string Titre du titre formaté
	 */
	public static function formatTitle($ptRowDocs)
	{
		$s = null;
		if ((isset($ptRowDocs)) && (count($ptRowDocs) > 0)) {
			$s = $ptRowDocs[0]->title;
		}
		return $s;
	}

	/**
	 * Formate les tomes d'un titre avec les numéros de tomes des documents.
	 *
	 * @param
	 *        	array of row_doc $ptRowDocs Tableau d'enregistrement de document
	 * @return string Les tomes du titre formaté
	 */
	public static function formatTitleNumbers($ptRowDocs)
	{
		$sTomes = null;
		if ((isset($ptRowDocs)) && (count($ptRowDocs) > 0)) {
			$size = count($ptRowDocs);
			for ($i = 0; $i < $size; ++ $i) {
				if ($ptRowDocs[$i]->number) {
					if ($i == 0) {
						$sTomes = '#' . $ptRowDocs[$i]->number;
					} else {
						$sTomes .= ', #' . $ptRowDocs[$i]->number;
					}
				}
			}
		}
		return $sTomes;
	}

	/**
	 * Renvoiee le genre d'un utilisateur sius forme textuelle.
	 *
	 * @param
	 *        	row_user L'utilisateur
	 * @return string Le genre Homme, Femme ou NULL si inconnu
	 */
	public static function getTextGender($oUser)
	{
		$gender = null;
		switch ($oUser->gender) {
			case 'M':
				$gender = 'Homme';
				break;
			case 'F':
				$gender = 'Femme';
				break;
		}
		return $gender;
	}

	/**
	 * Construit les données sélectionnées pour un composant SELECT MULTIPLE.
	 *
	 * @param
	 *        	array(id:int ->label:string) $ptAll Tableau clé/valeur de tous les éléments
	 * @param array(id:int->?) $ptSelected
	 *        	Tableau clé/valeur des éléments sélectionnés, les clés contiennent les identifiants
	 * @return array(id:int, label:string, selected:boolean)
	 */
	public static function buildOptionSelected($ptAll, $ptSelected)
	{
		$tSelected = array();
		foreach ($ptAll as $id => $label) {
			if (null == $ptSelected) {
				$tSelected[] = array(
					$id,
					$label,
					FALSE
				);
			} elseif (is_array($ptSelected)) {
				$tSelected[] = array(
					$id,
					$label,
					array_key_exists($id, $ptSelected)
				);
			} else {
				$tSelected[] = array(
					$id,
					$label,
					$id == $ptSelected
				);
			}
		}
		return $tSelected;
	}

	/**
	 * Transforme un tableau de tuples en un tableau pour un composant SELECT.
	 *
	 * @param unknown $ptRows
	 *        	Le tableau de tuples
	 * @param unknown $pKeyName
	 *        	Le nom du champ dans le tuple pour la clé de sélection
	 * @param string $pLabelFieldName
	 *        	Le nom du champ dans le tuple pour le libellé (Mettre à null pour utiliser une fonction)
	 * @param string $pLabelFunctionName
	 *        	Le nom de la fonction de rapppel dans le tuple pour le libellé sinon null
	 * @param boolean $pForceEmpty
	 *        	Si vrai insère une valeur vide dans le tableau
	 * @return array(id:int ->label:string)
	 */
	public static function toSelect($ptRows, $pKeyName, $pLabelFieldName = NULL, $pLabelFunctionName = NULL, $pForceEmpty = NULL)
	{
		$tSelect = array();
		if ((null != $pForceEmpty) && (true == $pForceEmpty)) {
			$tSelect[- 1] = '';
		}
		foreach ($ptRows as $oRow) {
			if (null != $pLabelFunctionName) {
				$tSelect[$oRow->$pKeyName] = call_user_func(array(
					$oRow,
					$pLabelFunctionName
				));
			} else {
				$tSelect[$oRow->$pKeyName] = $oRow->$pLabelFieldName;
			}
		}
		return $tSelect;
	}

	/**
	 * Construit le texte sufixe du titre d'une invitation en fonction de l'action en cours.
	 */
	public static function makeSuffixTitleInvitation()
	{
		switch (_root::getAction()) {
			case 'reader':
				$title = 'Lecteur';
				break;
			case 'responsible':
				$title = 'Responsable de groupe';
				break;
			case 'board':
				$title = 'Membre du comité de sélection';
				break;
			default:
				$title = '???' . _root::getModule() . '::' . _root::getAction() . '???';
				break;
		}
		return $title;
	}

	/**
	 * Génère l'URL d'une invitation.
	 */
	public static function generateURLInvitation($poInvitation)
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base');
		$url .= _root::getLink('autoreg::index', 
			array(
				'id' => $poInvitation->invitation_id,
				'key' => $poInvitation->invitation_key
			));
		return $url;
	}
}