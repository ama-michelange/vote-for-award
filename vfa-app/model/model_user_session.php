<?php

class model_user_session
{

	private static $tInstance = array();

	protected static function _getInstance($class)
	{
		if (array_key_exists($class, self::$tInstance) === false) {
			self::$tInstance[$class] = new $class();
		}
		return self::$tInstance[$class];
	}

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function create($pUser)
	{
		$oUserSession = new row_user_session();
		$oUserSession->setUser($pUser);
		$oRoles = model_role::getInstance()->findAllByUserId($pUser->user_id);
		$oUserSession->setRoles($oRoles);

		$oGroups = model_group::getInstance()->findAllByUserId($pUser->user_id);
		$oUserSession->setGroups($oGroups);
		$oReaderGroup = model_group::getInstance()->findByUserIdByRoleName($pUser->user_id, plugin_vfa::TYPE_READER);
		$oUserSession->setReaderGroup($oReaderGroup);

		$oValidAwards = model_award::getInstance()->findAllValidByUserId($pUser->user_id);
		$oUserSession->setValidAwards($oValidAwards);

		$oValidReaderAwards = array();
		$oValidBoardAwards = array();
		foreach ($oValidAwards as $oAward) {
			switch ($oAward->type) {
				case plugin_vfa::TYPE_AWARD_BOARD:
					$oValidBoardAwards[] = $oAward;
					break;
				case plugin_vfa::TYPE_AWARD_READER:
					$oValidReaderAwards[] = $oAward;
					break;
			}
		}
		$oUserSession->setValidBoardAwards($oValidBoardAwards);
		$oUserSession->setValidReaderAwards($oValidReaderAwards);

		$tAuthorizations = array();
		foreach ($oRoles as $oRole) {
			// _root::getLog()->log('AMA >>> $oRole = '.$oRole->role_name.', ID = '.$oRole->role_id);
			$oAuthorizations = $oRole->findAuthorizations();
			foreach ($oAuthorizations as $oAuth) {
				$module = $oAuth->module;
				$action = $oAuth->action;
				$key = $module . '::' . $action;
				$tAuthorizations[$module] = true;
				$tAuthorizations[$key] = true;
			}
		}
		$oUserSession->setAuthorizations($tAuthorizations);
		return $oUserSession;
	}
}

class row_user_session
{

	/**
	 * @var row_user L'utilisateur propriétaire du compte
	 */
	private $oUser = null;

	/**
	 * @var row_role[] Table des roles de l'utilisateur
	 */
	private $oRoles = null;

	/**
	 * @var string[] Table des noms de roles de l'utilisateur
	 */
	private $tRoles = null;

	/**
	 * @var string[] Table des habilitations de l'utilisateur
	 */
	private $tAuthorizations = null;

	/**
	 * @var row_group[] Table des groupes de l'utilisateur
	 */
	private $oGroups = null;

	/**
	 * @var row_group Le groupe de LECTEUR de l'utilisateur
	 */
	private $oReaderGroup = null;

	/**
	 * @var row_award[] Prix valides de l'utilisateur
	 */
	private $oValidAwards = null;

	/**
	 * @var row_award[] Prix de lecteur valides de l'utilisateur
	 */
	private $oValidReaderAwards = null;

	/**
	 * @var row_award[] Préselections valides de l'utilisateur
	 */
	private $oValidBoardAwards = null;

	/**
	 * Attribue l'utilisateur propriétaire du compte
	 *
	 * @param row_user $pUser
	 */
	public function setUser($pUser)
	{
		$this->oUser = $pUser;
	}

	/**
	 * Renvoie l'utilisateur propriétaire du compte
	 *
	 * @return row_user
	 */
	public function getUser()
	{
		return $this->oUser;
	}

	/**
	 * Attribue un tableau de roles
	 *
	 * @param row_role $pRoles
	 */
	public function setRoles($pRoles)
	{
		$this->oRoles = $pRoles;
		if (null != $pRoles) {
			$this->tRoles = array();
			foreach ($pRoles as $oRole) {
				$this->tRoles[$oRole->role_name] = $oRole->role_name;
			}
		}
	}

	/**
	 * Renvoie le tableau de roles
	 *
	 * @return row_role
	 */
	public function getRoles()
	{
		return $this->oRoles;
	}

	/**
	 * Renvoie le tableau des noms de roles
	 *
	 * @return string[]
	 */
	public function getNameRoles()
	{
		return $this->tRoles;
	}

	/**
	 * Attribue le tableau des habilitations de l'utilisateur
	 *
	 * @param array $ptAuthorizations
	 */
	public function setAuthorizations($ptAuthorizations)
	{
		$this->tAuthorizations = $ptAuthorizations;
	}

	/**
	 * Renvoie le tableau des habilitations de l'utilisateur
	 *
	 * @return array
	 */
	public function getAuthorizations()
	{
		return $this->tAuthorizations;
	}

	/**
	 * Attribue un tableau de groupes
	 *
	 * @param row_group $pGroups
	 */
	public function setGroups($pGroups)
	{
		$this->oGroups = $pGroups;
	}

	/**
	 * Renvoie le tableau de groupes
	 *
	 * @return row_group
	 */
	public function getGroups()
	{
		return $this->oGroups;
	}

	/**
	 * Renvoie le tableau des noms de groupes
	 *
	 * @return string[]
	 */
	public function getNameGroups()
	{
		$tGroups = array();
		if (null != $this->oGroups) {
			foreach ($this->oGroups as $oGroup) {
				$tGroups[$oGroup->group_name] = $oGroup->group_name;
			}
		}
		return $tGroups;
	}

	public function getSelectGroups()
	{
		$tSelect = array();
		foreach ($this->oGroups as $oRow) {
			$tSelect[$oRow->group_id] = $oRow->group_name;
		}
		return $tSelect;
	}

	/**
	 * Attribue un tableau de groupes de LECTEUR
	 *
	 * @param row_group $pGroup
	 */
	public function setReaderGroup($pGroup)
	{
		$this->oReaderGroup = $pGroup;
	}

	/**
	 * Renvoie le groupe de LECTEUR de l'utilisateur
	 *
	 * @return row_group
	 */
	public function getReaderGroup()
	{
		return $this->oReaderGroup;
	}

	/**
	 * Attribue un tableau de prix
	 *
	 * @param row_award[] $pValidAwards
	 */
	public function setValidAwards($pValidAwards)
	{
		$this->oValidAwards = $pValidAwards;
	}

	/**
	 * Renvoie le tableau de prix
	 *
	 * @return row_award[]
	 */
	public function getValidAwards()
	{
		return $this->oValidAwards;
	}

	/**
	 * Attribue un tableau de prix
	 *
	 * @param row_award[] $pValidAwards
	 */
	public function setValidReaderAwards($pValidAwards)
	{
		$this->oValidReaderAwards = $pValidAwards;
	}

	/**
	 * Renvoie le tableau de prix
	 *
	 * @return row_award[]
	 */
	public function getValidReaderAwards()
	{
		return $this->oValidReaderAwards;
	}

	/**
	 * Attribue un tableau de prix
	 *
	 * @param row_award[] $pValidAwards
	 */
	public function setValidBoardAwards($pValidAwards)
	{
		$this->oValidBoardAwards = $pValidAwards;
	}

	/**
	 * Renvoie le tableau de prix
	 *
	 * @return row_award[]
	 */
	public function getValidBoardAwards()
	{
		return $this->oValidBoardAwards;
	}

	public function getSelectValidAwards()
	{
		$tSelect = array();
		foreach ($this->oValidAwards as $oRow) {
			$tSelect[$oRow->award_id] = $oRow->award_name;
		}
		return $tSelect;
	}
}

