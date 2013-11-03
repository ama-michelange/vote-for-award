<?php

class model_account
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
		$oAccount = new row_account();
		$oAccount->setUser($pUser);
		$oRoles = model_role::getInstance()->findAllByUserId($pUser->user_id);
		$oAccount->setRoles($oRoles);
		
		$oGroups = model_group::getInstance()->findAllByUserId($pUser->user_id);
		$oAccount->setGroups($oGroups);
		$oReaderGroups = model_group::getInstance()->findAllByUserIdByType($pUser->user_id, plugin_vfa::GROUP_TYPE_READER);
		$oAccount->setReaderGroups($oReaderGroups);
		
		$oAwards = model_award::getInstance()->findAllByUserId($pUser->user_id);
		$oAccount->setAwards($oAwards);
		
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
		$oAccount->setAuthorizations($tAuthorizations);
		return $oAccount;
	}
}

class row_account
{

	/**
	 *
	 * @var row_user L'utilisateur propriétaire du compte
	 */
	private $oUser = null;

	/**
	 *
	 * @var row_role[] Table des roles de l'utilisateur
	 */
	private $oRoles = null;

	/**
	 *
	 * @var string[] Table des noms de roles de l'utilisateur
	 */
	private $tRoles = null;

	/**
	 *
	 * @var string[] Table des habilitations de l'utilisateur
	 */
	private $tAuthorizations = null;

	/**
	 *
	 * @var row_group[] Table des groupes de l'utilisateur
	 */
	private $oGroups = null;

	/**
	 *
	 * @var row_group[] Table des groupes de LECTEUR de l'utilisateur
	 */
	private $oReaderGroups = null;

	/**
	 *
	 * @var row_award[] Table des prix de l'utilisateur
	 */
	private $oAwards = null;

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
	 * @param row_group $pGroups        	
	 */
	public function setReaderGroups($pGroups)
	{
		$this->oReaderGroups = $pGroups;
	}

	/**
	 * Renvoie le tableau de groupes de LECTEUR
	 *
	 * @return row_group
	 */
	public function getReaderGroups()
	{
		return $this->oReaderGroups;
	}

	/**
	 * Attribue un tableau de prix
	 *
	 * @param row_award $pAwards        	
	 */
	public function setAwards($pAwards)
	{
		$this->oAwards = $pAwards;
		
		$nbAward = count($this->oAwards);
		if ($nbAward > 0) {
			$oAward = $this->oAwards[0];
			$this->setCurrentIdAward($oAward->award_id);
		}
	}

	/**
	 * Renvoie le tableau de prix
	 *
	 * @return row_award
	 */
	public function getAwards()
	{
		return $this->oAwards;
	}

	public function getSelectAwards()
	{
		$tSelect = array();
		foreach ($this->oAwards as $oRow) {
			$tSelect[$oRow->award_id] = $oRow->award_name;
		}
		return $tSelect;
	}
}

