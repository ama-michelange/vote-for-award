<?php

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
	 * @return array row_award
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY year DESC, name, type');
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->award_id] = $oRow->name;
		}
		return $tSelect;
	}

	/**
	 * @return array row_award
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
		$this->execute('DELETE FROM vfa_award_selections WHERE award_id=?', $pIdAward);
		// TODO A supprimer !
//		$sql = 'DELETE FROM vfa_titles ' . 'WHERE title_id NOT IN (SELECT title_id FROM vfa_award_titles)';
//		$this->execute($sql);
//		$sql = 'DELETE FROM vfa_title_docs ' . 'WHERE title_id NOT IN (SELECT title_id FROM vfa_titles)';
//		$this->execute($sql);
	}

	// TODO A conserver ?
	/**
	 * @param $pTitleId
	 * @return array row_award
	 */
	public function findAllByTitleId($pTitleId)
	{
		$sql = 'SELECT * FROM vfa_awards, vfa_award_titles ' . 'WHERE (vfa_award_titles.award_id = vfa_awards.award_id) ' .
			'AND (vfa_award_titles.title_id = ?) ' . 'ORDER BY vfa_awards.name';
		return $this->findMany($sql, $pTitleId);
	}

	/**
	 * @param $pDocId
	 * @return array row_award
	 */
	public function findAllByDocId($pDocId)
	{
		$sql = 'SELECT * FROM vfa_awards, vfa_award_titles, vfa_title_docs ' . 'WHERE (vfa_award_titles.award_id = vfa_awards.award_id) ' .
			'AND (vfa_award_titles.title_id = vfa_title_docs.title_id) ' . 'AND (vfa_title_docs.doc_id= ?) ORDER BY vfa_awards.name';
		return $this->findMany($sql, $pDocId);
	}

	/**
	 * @param $pSelectionId string|array
	 * @return array row_award
	 */
	public function findAllBySelectionId($pSelectionId)
	{
		$sql = 'SELECT * FROM vfa_awards WHERE (selection_id = ?)';
		if (is_array($pSelectionId)) {
		 $nbWhere = count($pSelectionId) -1;
			for ($i = 0; $i < $nbWhere; $i++) {
				$sql.=' OR (selection_id = ?)';
			}
		}
		$sql .= ' ORDER BY year DESC, name, type';
		return $this->findMany($sql, $pSelectionId);
	}

	/**
	 * @param $pUserId
	 * @return array row_award
	 */
	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT * FROM vfa_awards, vfa_user_awards ' . 'WHERE (vfa_user_awards.award_id = vfa_awards.award_id) ' .
			'AND (vfa_user_awards.user_id = ?)';
		return $this->findMany($sql, $pUserId);
	}

	/**
	 * @param $pYear
	 * @param $pName
	 * @param $pType
	 * @return row_award
	 */
	public function findByYearNameType($pYear, $pName, $pType)
	{
		$sql = 'SELECT * FROM ' . $this->sTable . ' WHERE (year=?) AND (name=?) AND (type=?)';
		return $this->findOne($sql, $pYear, $pName, $pType);
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
