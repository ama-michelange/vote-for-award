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

    const CATEGORY_INVITATION = 'INVITATION';
    const CATEGORY_MODIFY = 'MODIFY';
    const CATEGORY_VALIDATE = 'VALIDATE';

    const ROLE_BOARD = 'board';
    const ROLE_BOOKSELLER = 'bookseller';
    const ROLE_ORGANIZER = 'organizer';
    const ROLE_OWNER = 'owner';
    const ROLE_READER = 'reader';
    const ROLE_RESPONSIBLE = 'responsible';

    const TYPE_BOARD = 'board';
    const TYPE_READER = 'reader';
    const TYPE_RESPONSIBLE = 'responsible';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';

    const TYPE_AWARD_BOARD = 'PSBD';
    const TYPE_AWARD_READER = 'PBD';

    const STATE_OPEN = 'OPEN';
    const STATE_CLOSE = 'CLOSE';
    const STATE_SENT = 'SENT';
    const STATE_ACCEPTED = 'ACCEPTED';
    const STATE_REJECTED = 'REJECTED';
    const STATE_NOT_SENT = 'NOT_SENT';

    const PROCESS_INTIME = 'INTIME';
    const PROCESS_INTIME_VALIDATE = 'INTIME_VALIDATE';

    const MIN_NB_VOTE_AWARD_READER = 7;
    const MIN_NB_VOTE_AWARD_BOARD = 1;

    const CODE_NB_BALLOT = 'NbBallot';
    const CODE_NB_BALLOT_VALID = 'NbBallotValid';
    const CODE_NB_GROUP = 'NbGroup';
    const CODE_NB_REGISTRED = 'NbRegistered';

    /**
     * Déplace, s'il existe, l'article du début d'un titre à la fin du même titre.
     * Ex : 'La page blanche' devient 'Page blanche (La)'
     *
     * @param string $pTitle
     *         Titre à traiter
     * @return string Le titre traité
     */
    public static function pushArticle($pTitle)
    {
        $sTitleUp = trim(strtoupper($pTitle));
        $lenTitleUp = strlen($sTitleUp);
        $sTrimTitle = trim($pTitle);
        $sTitleRet = $sTrimTitle;

        if (false == empty($sTitleUp)) {
            if (($lenTitleUp > 4) && (0 == substr_compare($sTitleUp, 'LES ', 0, 4))) {
                $sTitleRet = ucfirst(substr($sTrimTitle, 4)) . ' (' . substr($sTrimTitle, 0, 3) . ')';
            } elseif (($lenTitleUp > 3) && (0 == substr_compare($sTitleUp, 'LE ', 0, 3))) {
                $sTitleRet = ucfirst(substr($sTrimTitle, 3)) . ' (' . substr($sTrimTitle, 0, 2) . ')';
            } elseif (($lenTitleUp > 3) && (0 == substr_compare($sTitleUp, 'LA ', 0, 3))) {
                $sTitleRet = ucfirst(substr($sTrimTitle, 3)) . ' (' . substr($sTrimTitle, 0, 2) . ')';
            } elseif (($lenTitleUp > 7) && (0 == substr_compare($sTitleUp, 'L&#039;', 0, 7))) {
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
        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = _root::getConfigVar('language.default');
        }
        plugin_i18n::load($_SESSION['lang']);
    }

    /**
     * Transforme une Date en DateTime à minuit (00:00:00).
     *
     * @param plugin_date $poDate La date
     * @return plugin_datetime La DateTime ou NULL si pb
     */
    public static function toDateTime($poDate)
    {
        $oDatetime = null;
        if (isset($poDate)) {
            $sDate = $poDate->toString('Y-m-d') . ' 00:00:00';
            $oDatetime = new plugin_datetime($sDate, 'Y-m-d H:i:s');
        }
        return $oDatetime;
    }

    /**
     * Transforme une chaine au format SGBD en date.
     *
     * @param string $pDate
     *         La date au format SGBD
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
     * Transforme une chaine au format SGBD en datetime.
     *
     * @param string $pDatetime
     *         La date au format SGBD
     * @return plugin_date La date ou NULL si pb
     */
    public static function toDateTimeFromSgbd($pDatetime)
    {
        $oDatetime = null;
        if (isset($pDatetime)) {
            $oDatetime = new plugin_datetime($pDatetime, 'Y-m-d H:i:s');
        }
        return $oDatetime;
    }

    /**
     * Change le format d'une date.
     *
     * @param string $pDate
     *         La date au format source
     * @param string $pFormatSrc
     *         Le format source
     * @param string $pFormatDst
     *         Le format de destination
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
     * @param string $pDate La horodate au format source
     * @param string $pFormatSrc Le format source
     * @param string $pFormatDst Le format de destination
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
     * @param string $pDate La date au format SGBD
     * @return string La date au format d'affichage
     */
    public static function toStringDateShow($pDate)
    {
        return self::changeDateFormat($pDate, 'Y-m-d', 'd/m/Y');
    }

    /**
     * Change le format d'une date et d'une heure SGBD vers le format d'affichage.
     *
     * @param string $pDate La date et heure au format SGBD
     * @return string La date et l'haure au format d'affichage
     */
    public static function toStringDateTimeShow($pDate)
    {
        return self::changeDatetimeFormat($pDate, 'Y-m-d H:i:s', 'd/m/Y H:i:s');
    }

    /**
     * Change le format d'une date et d'une heure SGBD vers le format d'affichage.
     *
     * @param string $pDate La date et heure au format SGBD
     * @return string La date et l'haure au format d'affichage
     */
    public static function toStringTimeShow($pDate)
    {
        return self::changeDatetimeFormat($pDate, 'Y-m-d H:i:s', 'H:i:s');
    }

    /**
     * Change le format d'une date d'affichage vers le format SGBD.
     *
     * @param string $pDate
     *         La date au format d'affichage
     * @return string La date au format SGBD
     */
    public static function toStringDateSgbd($pDate)
    {
        return self::changeDateFormat($pDate, 'd/m/Y', 'Y-m-d');
    }

    /**
     * La date courante au format du SGBD.
     *
     * @return string La date au format SGBD
     */
    public static function dateSgbd()
    {
        return date('Y-m-d');
    }

    /**
     * La date et heure courante au format du SGBD.
     *
     * @return string La date et heure au format SGBD
     */
    public static function dateTimeSgbd()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @param plugin_datetime $pDateTimeA
     * @param plugin_datetime $pDateTimeB
     * @return bool
     */
    public static function beforeDateTime($pDateTimeA, $pDateTimeB)
    {
        $a = $pDateTimeA->getMkTime();
        $b = $pDateTimeB->getMkTime();
        return $a < $b;
    }

    /**
     * @param plugin_datetime $pDateTimeA
     * @param plugin_datetime $pDateTimeB
     * @return bool
     */
    public static function afterDateTime($pDateTimeA, $pDateTimeB)
    {
        $a = $pDateTimeA->getMkTime();
        $b = $pDateTimeB->getMkTime();
        return $a > $b;
    }

    /**
     * @param plugin_date $pDateA
     * @param plugin_date $pDateB
     * @return bool
     */
    public static function afterDate($pDateA, $pDateB)
    {
        $a = $pDateA->getMkTime();
        $b = $pDateB->getMkTime();
        return $a > $b;
    }

    /**
     * @param plugin_date $pDateA
     * @param plugin_date $pDateB
     * @return bool
     */
    public static function beforeDate($pDateA, $pDateB)
    {
        $a = $pDateA->getMkTime();
        $b = $pDateB->getMkTime();
        return $a < $b;
    }

    /**
     * Date et heure courante
     * @return plugin_datetime
     */
    public static function now()
    {
        return new plugin_datetime(date('Y-m-d H:i:s', time()));
    }

    /**
     * Date courante
     * @return plugin_date
     */
    public static function today()
    {
        return new plugin_date(date('Y-m-d'));
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
            for ($i = 0; $i < $size; ++$i) {
                $tRet[$pArray[$i]] = $pArray[$i];
            }
        }
        return $tRet;
    }

    /**
     * Formate le nom d'un titre avec le 1er enregistrement des documents.
     *
     * @param
     *         array of row_doc $ptRowDocs Tableau d'enregistrement de document
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
     *         array of row_doc $ptRowDocs Tableau d'enregistrement de document
     * @return string Les tomes du titre formaté
     */
    public static function formatTitleNumbers($ptRowDocs)
    {
        $sTomes = null;
        if ((isset($ptRowDocs)) && (count($ptRowDocs) > 0)) {
            $size = count($ptRowDocs);
            for ($i = 0; $i < $size; ++$i) {
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
     *         row_user L'utilisateur
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
     *         array(id:int ->label:string) $ptAll Tableau clé/valeur de tous les éléments
     * @param array(id:int->?) $ptSelected
     *         Tableau clé/valeur des éléments sélectionnés, les clés contiennent les identifiants
     * @return array(id:int, label:string, selected:boolean)
     */
    public static function buildOptionSelected($ptAll, $ptSelected)
    {
        $tSelected = array();
        foreach ($ptAll as $id => $label) {
            if (null == $ptSelected) {
                $tSelected[] = array($id, $label, FALSE);
            } elseif (is_array($ptSelected)) {
                $tSelected[] = array($id, $label, array_key_exists($id, $ptSelected));
            } else {
                $tSelected[] = array($id, $label, $id == $ptSelected);
            }
        }
        return $tSelected;
    }

    /**
     * Transforme un tableau de tuples en un tableau pour un composant SELECT.
     *
     * @param unknown $ptRows
     *         Le tableau de tuples
     * @param unknown $pKeyName
     *         Le nom du champ dans le tuple pour la clé de sélection
     * @param string $pLabelFieldName
     *         Le nom du champ dans le tuple pour le libellé (Mettre à null pour utiliser une fonction)
     * @param string $pLabelFunctionName
     *         Le nom de la fonction de rappel dans le tuple pour le libellé sinon null
     * @param boolean $pForceEmpty
     *         Si vrai insère une valeur vide dans le tableau
     * @return array(id:int ->label:string)
     */
    public static function toSelect($ptRows, $pKeyName, $pLabelFieldName = NULL, $pLabelFunctionName = NULL, $pForceEmpty = NULL)
    {
        $tSelect = array();
        if ((null != $pForceEmpty) && (true == $pForceEmpty)) {
            $tSelect[-1] = '';
        }
        foreach ($ptRows as $oRow) {
            if (null != $pLabelFunctionName) {
                $tSelect[$oRow->$pKeyName] = call_user_func(array($oRow, $pLabelFunctionName));
            } else {
                $tSelect[$oRow->$pKeyName] = $oRow->$pLabelFieldName;
            }
        }
        return $tSelect;
    }

    /**
     * @param int $pBirthYear
     * @return array
     */
    public static function buildSelectedBirthYears($pBirthYear = null)
    {
        $tYear = array();
        $date = new plugin_date(date('Y-m-d'));
        $date->removeYear(10);
        for ($i = 0; $i < 91; $i++) {
            if (($pBirthYear) && ($pBirthYear == $date->getYear())) {
                $tYear[$date->getYear()] = true;
            } else {
                $tYear[$date->getYear()] = false;
            }
            $date->removeYear(1);
        }
        return $tYear;
    }

    /**
     * Génère l'URL d'une invitation.
     */
    public static function generateURLInvitation($poInvitation)
    {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . _root::getConfigVar('path.base');
        $url .= _root::getLink('autoreg::index', array('id' => $poInvitation->invitation_id, 'key' => $poInvitation->invitation_key), false);
        // _root::getLog()->log('generateURLInvitation : ' . $url);
        return $url;
    }

    /**
     * Génère un code d'inscription.
     * @param $pYear
     * @param $pName
     * @param $pPrefix
     * @return string
     */
    public static function generateRegistrationCode($pYear, $pName, $pPrefix = null)
    {
        $lenName = 9;
        $time = time();
        $year = substr($pYear, 0, 4);
        $prefix = '';
        if ($pPrefix) {
            $prefix = trim(substr($pPrefix, 0, 3));
        }
        $gname = self::stripAccents(trim(substr($pName, 0, $lenName)));
        $gname = implode(explode(' ', $gname));
        if ($time % 2 == 0) {
            $gname = strtoupper($gname);
        } else {
            $gname = strtolower($gname);
        }
        $fcode = $prefix . $year . $gname . substr($time, -2);

        while (strlen($fcode) > 15) {
            $lenName--;
            $fcode = $prefix . $year . substr($gname, 0, $lenName) . substr($time, -2);
        }
        $sha = sha1($fcode . rand(0, $time));
        $code = $fcode . substr($sha, strlen($fcode) - 20);
//		var_dump(strlen($code));
//		var_dump($code);
        return $code;
    }

    /**
     * Construit le texte de l'invitation en fonction de son type.
     *
     * @param row_invitation $poInvitation
     *         L'invitation
     * @param boolean $pHtml
     *         Vrai pour inclure du HTML dans le texte
     * @return string Le texte de l'invitation
     */
    public static function buildTextInvitation($poInvitation, $pHtml)
    {
        $tAwards = $poInvitation->findAwards();
        $oGroup = $poInvitation->findGroup();
        $oCreatedUser = $poInvitation->findCreatedUser();
        $last_name = $oCreatedUser->last_name;
        $first_name = $oCreatedUser->first_name;
        $login = $oCreatedUser->login;
        if ((true == isset($last_name)) && (strlen(trim($last_name)) > 0)) {
            $creator = trim($first_name . ' ' . $last_name);
        } else {
            $creator = $login;
        }
        $cr = '\n';
        if ($pHtml) {
//			$cr = '<br/>';
            $cr = '';
        }

        $tPrix = array();
        foreach ($tAwards as $oAward) {
            $tPrix[] = $oAward->toString();
        }
        natsort($tPrix);
        $xPrix = 'au prix suivant ';
        if (count($tAwards) > 1) {
            $xPrix = 'aux prix suivants ';
        }
        switch ($poInvitation->type) {
            case plugin_vfa::TYPE_BOARD:
                $textInvit = sprintf('%1$s, l\'organisateur du Prix de la BD INTER CE, %2$s%2$svous invite à devenir membre du Comité de sélection.',
                    $creator, $cr);
                break;
            case plugin_vfa::TYPE_READER:
//				$textInvit = sprintf('%1s, le correspondant du Prix de la BD INTER CE, vous invite à vous inscrire %2s : ', $creator, $xPrix);
                $textInvit = sprintf('%1$s, le correspondant du Prix de la BD INTER CE, %2$s%2$svous invite à participer au prix.', $creator,
                    $cr);
                break;
            case plugin_vfa::TYPE_RESPONSIBLE:
                $textInvit = sprintf('%1$s, l\'organisateur du Prix de la BD INTER CE, %3$s%3$svous invite à devenir Correspondant du groupe %2$s %3$s%3$set à vous inscrire au prix.',
                    $creator, $oGroup->group_name, $cr);
                break;
            default:
                $textInvit = '';
                break;
        }

//		$beforeHtml = '';
//		$afterHtml = '';
//		if ($pHtml) {
//			$textInvit .= '<strong>';
//			$beforeHtml = '<span class="nowrap">';
//			$afterHtml = '</span>';
//		}
//		$i = 0;
//		foreach ($tPrix as $prix) {
//			if ($i > 0) {
//				$textInvit .= ', ';
//			}
//			$textInvit .= sprintf('%1s%2s%3s', $beforeHtml, $prix, $afterHtml);
//			$i++;
//		}
//		if ($pHtml) {
//			$textInvit .= '</strong>';
//		}
        return $textInvit;
    }

    /**
     * Construit le titre de l'invitation en fonction de sa catégorie et de son type.
     *
     * @param row_invitation $poInvitation
     *         L'invitation
     * @param bool $pEmailSubject
     * @return string Le titre de l'invitation
     */
    public static function buildTitleInvitation($poInvitation, $pEmailSubject = false)
    {
        $titleInvit = '';
        if (true == $pEmailSubject) {
            $titleInvit = '[' . _root::getConfigVar('vfa-app.title') . '] ';
        }
        switch ($poInvitation->category) {
            case plugin_vfa::CATEGORY_INVITATION:
                switch ($poInvitation->type) {
                    case plugin_vfa::TYPE_BOARD:
                        $titleInvit .= 'Invitation pour participer au Comité de sélection';
                        break;
                    case plugin_vfa::TYPE_READER:
                        $titleInvit .= 'Invitation pour voter au Prix de la BD INTER CE';
                        break;
                    case plugin_vfa::TYPE_RESPONSIBLE:
                        $titleInvit .= 'Invitation pour devenir Correspondant de votre groupe et voter au Prix de la BD INTER CE';
                        break;
                }
                break;
            case plugin_vfa::CATEGORY_VALIDATE:
                switch ($poInvitation->type) {
                    case plugin_vfa::TYPE_EMAIL:
                        $titleInvit .= 'Changement d\'adresse Email';
                        break;
                }
                break;
            case plugin_vfa::CATEGORY_MODIFY:
                switch ($poInvitation->type) {
                    case plugin_vfa::TYPE_PASSWORD:
                        $titleInvit .= 'Mot de passe oublié';
                        break;
                }
                break;
        }
        //		_root::getLog()->log($titleInvit);
        return $titleInvit;
    }

    /**
     * Envoie un mail en fonction de l'autorisation dans la configuration ou le simule.
     * <p>Si la configuration l'autorise (voir 'vfa-app.mail.enabled'), l'email est transmit au système et le retour est celui du système.
     * Sinon c'est une simulation et le retour est toujours vrai.</p>
     * @param plugin_mail $poMail
     * @return bool Vrai si l'email est réellemnt envoyé au niveau système ou simulé
     */
    public static function sendEmail($poMail)
    {
        // Envoi le mail
        try {
            if (_root::getConfigVar('vfa-app.mail.enabled')) {
                $sent = $poMail->send();
            } else {
                $sent = true;
            }
        } catch (Exception $e) {
            _root::getLog()->error($e->getCode() . ':' . $e->getMessage());
            $sent = false;
        }
        return $sent;
    }

    /**
     * Vérifie la saisie d'un mot de passe et de sa confirmation et le sauvegarde dans le champ 'password' s'il est correct.
     * @param abstract_row $poRow L'objet de table concerné
     * @param string $pNewPassword Le mot de passe saisi
     * @param string $pConfirmPassword La confirmation du mot de passe saisi
     * @return bool Vrai si le mot de passe à la taille souhaitée et s'il est identique à sa confirmation
     */
    public static function checkSavePassword($poRow, $pNewPassword, $pConfirmPassword)
    {
        $ok = false;
        if (null != $pNewPassword) {
            $lenPassword = strlen($pNewPassword);
            if (($lenPassword < 7) OR ($lenPassword > 50)) {
                $poRow->setMessages(array('newPassword' => array('badSize')));
            } else {
                if ($pNewPassword === $pConfirmPassword) {
                    $poRow->password = sha1($pNewPassword . _root::getConfigVar('security.salt'));
                    $ok = true;
                } else {
                    $poRow->setMessages(array('newPassword' => array('isEqualKO'), 'confirmPassword' => array('isEqualKO')));
                }
            }
        }
        return $ok;
    }

    public static function cryptPassword($pPassword)
    {
        return sha1($pPassword . _root::getConfigVar('security.salt'));
    }

    public static function is_ssl()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS']))
                return true;
            if ('1' == $_SERVER['HTTPS'])
                return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }

    /**
     * Renvoi les identifiants du tableau d'abstract_row sous forme de chaine séparé par une virgule
     * @param array of abstract_row $pArrayRows
     * @return string
     */
    public static function getIds($pArrayRows)
    {
        $ids = '';
        foreach ($pArrayRows as $row) {
            if (strlen($ids) > 0) {
                $ids .= ',';
            }
            $ids .= $row->getId();
        }
        return $ids;
    }


    public static function stripAccents($texte)
    {
        $texte = str_replace(
            array(
                'à', 'â', 'ä', 'á', 'ã', 'å',
                'î', 'ï', 'ì', 'í',
                'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
                'ù', 'û', 'ü', 'ú',
                'é', 'è', 'ê', 'ë',
                'ç', 'ÿ', 'ñ',
                'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
                'Î', 'Ï', 'Ì', 'Í',
                'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø',
                'Ù', 'Û', 'Ü', 'Ú',
                'É', 'È', 'Ê', 'Ë',
                'Ç', 'Ÿ', 'Ñ'
            ),
            array(
                'a', 'a', 'a', 'a', 'a', 'a',
                'i', 'i', 'i', 'i',
                'o', 'o', 'o', 'o', 'o', 'o',
                'u', 'u', 'u', 'u',
                'e', 'e', 'e', 'e',
                'c', 'y', 'n',
                'A', 'A', 'A', 'A', 'A', 'A',
                'I', 'I', 'I', 'I',
                'O', 'O', 'O', 'O', 'O', 'O',
                'U', 'U', 'U', 'U',
                'E', 'E', 'E', 'E',
                'C', 'Y', 'N'
            ), $texte);
        return $texte;
    }
}
