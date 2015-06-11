<?php

/**
 * Class model_award
 */
class model_award extends abstract_model
{

	protected $sClassRow = 'row_award';

	protected $sTable = 'vfa_awards';

	protected $sConfig = 'mysql';

	protected $tId = array('award_id');

	/**
	 * @return model_award
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_award
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE award_id=?', $uId);
	}

	/**
	 * @return row_award[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY YEAR DESC, NAME, type');
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->getId()] = $oRow->toString();
		}
		return $tSelect;
	}

	/**
	 * @param $pType
	 * @return row_award[]
	 */
	public function findAllByType($pType)
	{
		$sql = 'SELECT * FROM vfa_awards ' . 'WHERE (vfa_awards.type = ?) ' . 'ORDER BY start_date DESC, name';
		return $this->findMany($sql, $pType);
	}

	/**
	 * Supprime
	 *
	 * @param string $pIdAward
	 */
	public function deleteAwardCascades($pIdAward)
	{
		$this->execute('DELETE FROM vfa_awards WHERE award_id=?', $pIdAward);
//		$this->execute('DELETE FROM vfa_award_selections WHERE award_id=?', $pIdAward);
	}

	/**
	 * @param $pSelectionId string|array
	 * @return row_award[]
	 */
	public function findAllBySelectionId($pSelectionId)
	{
		$sql = 'SELECT * FROM vfa_awards WHERE (selection_id = ?)';
		if (is_array($pSelectionId)) {
			$nbWhere = count($pSelectionId) - 1;
			for ($i = 0; $i < $nbWhere; $i++) {
				$sql .= ' OR (selection_id = ?)';
			}
		}
		$sql .= ' ORDER BY year DESC, name, type';
		return $this->findMany($sql, $pSelectionId);
	}

	/**
	 * @param $pGroupId
	 * @return row_award[]
	 */
	public function findAllByGroupId($pGroupId)
	{
		$sql = 'SELECT * FROM vfa_awards, vfa_group_awards ' . 'WHERE (vfa_group_awards.award_id = vfa_awards.award_id) ' .
			'AND (vfa_group_awards.group_id = ?) ORDER BY vfa_awards.public DESC, vfa_awards.year DESC, vfa_awards.name';
		return $this->findMany($sql, $pGroupId);
	}

	/**
	 * @param $pUserId
	 * @return row_award[]
	 */
	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT * FROM vfa_awards, vfa_user_awards ' . 'WHERE (vfa_user_awards.award_id = vfa_awards.award_id) ' .
			'AND (vfa_user_awards.user_id = ?) ORDER BY YEAR DESC, NAME, type';
		return $this->findMany($sql, $pUserId);
	}

	/**
	 * @param $pUserId
	 * @param string|null $pType
	 * @return row_award[]
	 */
	public function findAllInProgressByUserId($pUserId, $pType = null)
	{
		$andType = '';
		if (null != $pType) {
			$andType = ' AND (vfa_awards.type = \'' . $pType . '\')';
		}
		$sql = 'SELECT * FROM vfa_awards, vfa_user_awards ' . 'WHERE (vfa_user_awards.award_id = vfa_awards.award_id) ' .
			'AND (vfa_user_awards.user_id = ?) AND (? <= vfa_awards.end_date)' . $andType . ' ORDER BY vfa_awards.public DESC';
		return $this->findMany($sql, $pUserId, plugin_vfa::dateSgbd());
	}

	/**
	 * @param string|null $pType
	 * @param boolean|null $pPublic
	 * @return row_award[]
	 */
	public function findAllInProgress($pType = null, $pPublic = null)
	{
		$andType = '';
		if (null != $pType) {
			$andType = ' AND (vfa_awards.type = \'' . $pType . '\')';
		}
		$andPublic = '';
		if (null != $pPublic) {
			$p = 1;
			if (false == $pPublic) {
				$p = 0;
			}
			$andPublic = ' AND (vfa_awards.public =  ' . $p . ')';
		}
		$sql = 'SELECT * FROM vfa_awards WHERE (? <= vfa_awards.end_date)' . $andType . $andPublic .
			' ORDER BY vfa_awards.public DESC, vfa_awards.type, vfa_awards.year DESC, vfa_awards.name';
		return $this->findMany($sql, plugin_vfa::dateSgbd());
	}

	/**
	 * @param boolean|null $pPublic
	 * @param string|null $pType
	 * @return row_award[]
	 */
	public function findAllCompleted($pPublic = null, $pType = plugin_vfa::TYPE_AWARD_READER)
	{
		$andPublic = '';
		if (null != $pPublic) {
			$p = 1;
			if (false == $pPublic) {
				$p = 0;
			}
			$andPublic = ' AND (vfa_awards.public =  ' . $p . ')';
		}
		$andType = '';
		if (null != $pType) {
			$andType = ' AND (vfa_awards.type = \'' . $pType . '\')';
		}
		$sql = 'SELECT * FROM vfa_awards WHERE (? > vfa_awards.end_date)' . $andPublic . $andType .
			' ORDER BY vfa_awards.public DESC, vfa_awards.type, vfa_awards.year DESC, vfa_awards.name';
		return $this->findMany($sql, plugin_vfa::dateSgbd());
	}

	/**
	 * @param $pYear
	 * @param $pName
	 * @param $pType
	 * @return row_award
	 */
	public function findByYearNameType($pYear, $pName, $pType)
	{
		$sql = 'SELECT * FROM ' . $this->sTable . ' WHERE (YEAR=?) AND (NAME=?) AND (type=?)';
		return $this->findOne($sql, $pYear, $pName, $pType);
	}

	/**
	 * @param $pAwardId
	 * @param null $pGroupId
	 * @return int
	 */
	public function countUser($pAwardId, $pGroupId = null)
	{
		$ret = 0;
		if ($pGroupId) {
			$sql = 'SELECT count(vfa_user_awards.user_id) FROM vfa_user_awards,vfa_user_groups WHERE (vfa_user_awards.award_id = ?)' .
				' AND (vfa_user_awards.user_id = vfa_user_groups.user_id) AND (vfa_user_groups.group_id = ?) ';
			$res = $this->execute($sql, $pAwardId, $pGroupId);
		} else {
			$sql = 'SELECT count(*) FROM vfa_user_awards  WHERE (vfa_user_awards.award_id = ?)';
			$res = $this->execute($sql, $pAwardId);
		}
		while ($row = mysql_fetch_row($res)) {
			$ret = $row[0];
		}
		mysql_free_result($res);
		return $ret;
	}

	/**
	 * @param $pAwardId
	 * @return int
	 */
	public function countGroup($pAwardId)
	{
		$ret = 0;
		$sql = 'SELECT count(DISTINCT vfa_user_groups.group_id) FROM vfa_user_groups, vfa_user_awards' .
			' WHERE (vfa_user_awards.award_id = ?) AND (vfa_user_awards.user_id = vfa_user_groups.user_id)';
		$res = $this->execute($sql, $pAwardId);
		while ($row = mysql_fetch_row($res)) {
			$ret = $row[0];
		}
		mysql_free_result($res);
		return $ret;
	}
}

class row_award extends abstract_row
{

	protected $sClassModel = 'model_award';

	protected $tMessages = null;

	public function findTitles()
	{
		$tArray = null;
		if ((null != $this->award_id) && (null != $this->selection_id)) {
			$tArray = model_title::getInstance()->findAllBySelectionId($this->selection_id);
		}
		return $tArray;
	}

	public function isInProgress()
	{
		$inProgress = false;
		if (null != $this->award_id) {
			$endDate = plugin_vfa::toDateTime(plugin_vfa::toDateFromSgbd($this->end_date));
			$now = plugin_vfa::now();
			// Si le prix est termminé
			if (false == plugin_vfa::afterDateTime($now, $endDate)) {
				$inProgress = true;
			}
		}
		return $inProgress;
	}

	public function getSelectTitles()
	{
		$tArray = null;
		if ((null != $this->award_id) && (null != $this->selection_id)) {
			$tArray = model_title::getInstance()->getSelectBySelectionId($this->selection_id);
		}
		return $tArray;
	}

	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		$oPluginValid->isNotEmpty('year');
		$oPluginValid->matchExpression('year', '/^[0-9]+$/');
		$oPluginValid->isUpperOrEqualThan('year', 2000);
		$oPluginValid->isNotEmpty('name');
		$oPluginValid->isNotEmpty('start_date');
		$oPluginValid->isNotEmpty('end_date');
		$oPluginValid->isDateBefore('start_date', 'end_date');
		$oPluginValid->isDateAfter('end_date', 'start_date');

		return $oPluginValid;
	}

	public function isValid()
	{
		return $this->getCheck()->isValid();
	}

	public function getListError()
	{
		$t = $this->getCheck()->getListError();
		return $t;
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

	public function toString()
	{
		$s = $this->getTypeString() . ' ' . $this->name . ' ' . $this->year;
		return $s;
	}

	public function toStringName()
	{
		$s = $this->name . ' ' . $this->year;
		return $s;
	}

	public function toStringWithPrefix()
	{
		$s = $this->getPrefix() . ' ' . $this->toString();
		return $s;
	}

	public function getPrefix()
	{
		switch ($this->type) {
			case 'PSBD':
				$s = 'de la';
				break;
			default:
				$s = 'du';
				break;
		}
		return $s;
	}

	public function getTypeString()
	{
		switch ($this->type) {
			case 'PSBD':
				$s = 'Présélection';
				break;
			default:
				$s = 'Prix';
				break;
		}
		return $s;
	}

	public function getTypeNameString()
	{
		$s = $this->getTypeString() . ' ' . $this->name;
		return $s;
	}

	public function getShowString()
	{
		if ($this->public) {
			$s = 'Public';
		} else {
			$s = 'Privé';
		}
		return $s;
	}

	public function getTypeShowString()
	{
		$s = $this->getTypeString();
		switch ($this->type) {
			case 'PSBD':
				if ($this->public) {
					$s = $s . ' publique';
				} else {
					$s = $s . ' privée';
				}
				break;
			default:
				if ($this->public) {
					$s = $s . ' public';
				} else {
					$s = $s . ' privé';
				}
				break;
		}
		return $s;
	}
}
