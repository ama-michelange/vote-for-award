<?php

class model_group extends abstract_model
{

	protected $sClassRow = 'row_group';

	protected $sTable = 'vfa_groups';

	protected $sConfig = 'mysql';

	protected $tId = array('group_id');

	private $tTypeGroups;

	/**
	 * @return model_group
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId string
	 * @return row_group
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE group_id=?', $uId);
	}

	/**
	 * @return row_group[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY group_name');
	}

	/**
	 * @param $ptId array string
	 * @return row_group[]
	 */
	public function findAllByIds($ptId)
	{
		$ret = null;
		if ($ptId) {
			$sql = 'SELECT * FROM ' . $this->sTable;
			$sql .= ' WHERE';
			$i = 0;
			foreach ($ptId as $id) {
				if ($i > 0) {
					$sql .= ' OR';
				}
				$sql .= ' group_id=' . $id;
				$i++;
			}
			$ret = $this->findMany($sql);
		}
		return $ret;
	}

	/**
	 * @param $pUserId
	 * @return row_group[]
	 */
	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT vfa_groups.group_id, vfa_groups.group_name, vfa_groups.role_id_default FROM vfa_groups, vfa_user_groups ';
		$sql .= 'WHERE (vfa_user_groups.group_id = vfa_groups.group_id) AND (vfa_user_groups.user_id = ?) ';
		$sql .= 'ORDER BY vfa_groups.group_name';
		return $this->findMany($sql, $pUserId);
	}

	/**
	 * @param $pRoleName
	 * @return row_group[]
	 */
	public function findAllByRoleName($pRoleName)
	{
		$sql = 'SELECT vfa_groups.group_id, vfa_groups.group_name, vfa_groups.role_id_default FROM vfa_groups, vfa_roles ';
		$sql .= 'WHERE (vfa_groups.role_id_default = vfa_roles.role_id) AND (vfa_roles.role_name = ?) ';
		$sql .= 'ORDER BY vfa_groups.group_name';
		return $this->findMany($sql, $pRoleName);
	}

	/**
	 * @param $pUserId
	 * @param $pRoleName
	 * @return row_group
	 */
	public function findByUserIdByRoleName($pUserId, $pRoleName)
	{
		$sql = 'SELECT vfa_groups.group_id, vfa_groups.group_name, vfa_groups.role_id_default FROM vfa_groups, vfa_roles, vfa_user_groups ';
		$sql .= 'WHERE (vfa_groups.role_id_default = vfa_roles.role_id) ';
		$sql .= 'AND (vfa_user_groups.group_id = vfa_groups.group_id) ';
		$sql .= 'AND (vfa_user_groups.user_id = ?) ';
		$sql .= 'AND (vfa_roles.role_name = ?)';
		return $this->findOne($sql, $pUserId, $pRoleName);
	}

	/**
	 * @param $pUserId
	 * @param $pGroupId
	 * @return row_group
	 */
	public function findByUserIdByGroupId($pUserId, $pGroupId)
	{
		$sql = 'SELECT vfa_groups.group_id, vfa_groups.group_name, vfa_groups.role_id_default FROM vfa_groups, vfa_user_groups ';
		$sql .= 'WHERE (vfa_user_groups.group_id = vfa_groups.group_id) ';
		$sql .= 'AND (vfa_user_groups.user_id = ?) ';
		$sql .= 'AND (vfa_user_groups.group_id = ?)';
		return $this->findOne($sql, $pUserId, $pGroupId);
	}

	/**
	 * @param $pName
	 * @return row_group
	 */
	public function findByName($pName)
	{
		return $this->findOne('SELECT * FROM' . ' ' . $this->sTable . ' WHERE group_name=?', $pName);
	}

	/**
	 * @param $pIdGroup
	 * @return row_award
	 */
	public function findAllRegistryInProgressAwards($pIdGroup)
	{
		$tAwards = array();
		$tGroupAwards = $this->findMany('SELECT * FROM vfa_group_awards WHERE group_id=?', $pIdGroup);
		foreach ($tGroupAwards as $groupAward) {
			$oAward = model_award::getInstance()->findById($groupAward->award_id);
			$end = new plugin_date($oAward->end_date);
			if (true == plugin_vfa::beforeDate(plugin_vfa::today(), $end)) {
				$tAwards[] = $oAward;
			}
		}
		return $tAwards;
	}

	/**
	 * @param int $pIdGroup
	 * @param int[] $pIdAwards
	 */
	public function saveGroupAwards($pIdGroup, $pIdAwards)
	{
		$this->execute('DELETE FROM vfa_group_awards WHERE group_id=?', $pIdGroup);
		if ($pIdAwards) {
			foreach ($pIdAwards as $idAward) {
				$this->execute('INSERT INTO vfa_group_awards (group_id, award_id) VALUES (?,?)', $pIdGroup, $idAward);
			}
		}
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->group_id] = $oRow->group_name;
		}
		return $tSelect;
	}

	/**
	 * @param $pIdRole
	 * @return string
	 */
	public function getStringTypeGroup($pIdRole)
	{
		if (!isset($this->tTypeGroups[$pIdRole])) {
			if (!isset($this->tTypeGroups)) {
				$this->tTypeGroups = array();
			}
			$oRole = model_role::getInstance()->findById($pIdRole);
			$this->tTypeGroups[$pIdRole] = plugin_i18n::get('group.' . $oRole->role_name);
		}
		return $this->tTypeGroups[$pIdRole];
	}

}

class row_group extends abstract_row
{

	protected $sClassModel = 'model_group';

	protected $tMessages = null;

	public function findAwards()
	{
		return model_award::getInstance()->findAllByGroupId($this->group_id);
	}

	public function getAwardIds()
	{
		$ids = '';
		$tAwards = $this->findAwards();
		foreach ($tAwards as $award) {
			if (strlen($ids) > 0) {
				$ids .= ',';
			}
			$ids .= $award->getId();
		}
		return $ids;
	}

	public function findUsers()
	{
		return model_user::getInstance()->findAllByGroupId($this->group_id);
	}

	public function countUsers()
	{
		return model_user::getInstance()->countByGroupId($this->group_id);
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->group_name;
	}

	/**
	 * @return string
	 */
	public function getI18nStringType()
	{
		return model_group::getInstance()->getStringTypeGroup($this->role_id_default);
	}

	/**
	 * @return string
	 */
	public function getRoleString()
	{
		return model_role::getInstance()->getI18nStringRole($this->role_id_default);
	}

	/**
	 * @return string
	 */
	public function getAwardsString()
	{
		$ret = '';
		$tAwards = $this->findAwards();
		$i = 0;
		foreach ($tAwards as $oAward) {
			if ($i > 0) {
				$ret .= ', ';
			}
			$ret .= $oAward->toString();
			$i++;
		}
		return $ret;
	}

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('group_name');
		$oPluginValid->isNotEmpty('role_id_default');
		return $oPluginValid;
	}

	public function isValid()
	{
		return $this->getCheck()->isValid();
	}

	public function getListError()
	{
		return $this->getCheck()->getListError();
	}

	public function setMessages($ptMessages)
	{
		$this->tMessages = $ptMessages;
	}

	public function getMessages()
	{
		if (null != $this->tMessages) {
			return $this->tMessages;
		}
		return $this->getListError();
	}
}
