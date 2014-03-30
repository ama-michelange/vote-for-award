<?php

class model_selection extends abstract_model
{

	protected $sClassRow = 'row_selection';

	protected $sTable = 'vfa_selections';

	protected $sConfig = 'mysql';

	protected $tId = array('selection_id');

	/**
	 * @return model_selection
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_selection
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE selection_id=?', $uId);
	}

	/**
	 * @return array row_selection
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY year DESC, name');
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->selection_id] = $oRow->toString();
		}
		return $tSelect;
	}

	public function getSelectById($pSelectionId)
	{
		$oRow = $this->findById($pSelectionId);
		$tSelect = array();
		$tSelect[$oRow->selection_id] = $oRow->toString();
		return $tSelect;
	}

	/**
	 * @param $pDate
	 * @return array string
	 */
	// FIXME A conserver ?
	public function getSelectByDate($pDate)
	{
		$tab = $this->findAllByDate($pDate);
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->selection_id] = $oRow->name;
		}
		return $tSelect;
	}

	/**
	 * @param $pDate
	 * @return array row_selection
	 */
	// FIXME A conserver ?
	public function findAllByDate($pDate)
	{
		$sql = 'SELECT * FROM vfa_selections WHERE (year = ?)';
		return $this->findMany($sql, $pDate);
	}

	/**
	 * @param $pType
	 * @return array row_selection
	 */
	public function findAllByType($pType)
	{
		$sql = 'SELECT * FROM vfa_selections WHERE (vfa_selections.type = ?) ORDER BY name';
		return $this->findMany($sql, $pType);
	}


	/**
	 * Supprime
	 *
	 * @param string $pIdselection
	 */
	public function deleteselectionCascades($pIdselection)
	{
		$this->execute('DELETE FROM vfa_selections WHERE selection_id=?', $pIdselection);
		$this->execute('DELETE FROM vfa_selection_titles WHERE selection_id=?', $pIdselection);
		$sql = 'DELETE FROM vfa_titles ' . 'WHERE title_id NOT IN (SELECT title_id FROM vfa_selection_titles)';
		$this->execute($sql);
		$sql = 'DELETE FROM vfa_title_docs ' . 'WHERE title_id NOT IN (SELECT title_id FROM vfa_titles)';
		$this->execute($sql);
	}

	public function findAllByTitleId($pTitleId)
	{
		$sql = 'SELECT * FROM vfa_selections, vfa_selection_titles ' .
			'WHERE (vfa_selection_titles.selection_id = vfa_selections.selection_id) ' .
			'AND (vfa_selection_titles.title_id = ?) ' . 'ORDER BY vfa_selections.name';
		return $this->findMany($sql, $pTitleId);
	}

	/**
	 * @param $pDocId
	 * @return array row_selection
	 */
	public function findAllByDocId($pDocId)
	{
		$sql = 'SELECT * FROM vfa_selections, vfa_selection_titles, vfa_title_docs ' .
			'WHERE (vfa_selection_titles.selection_id = vfa_selections.selection_id) ' .
			'AND (vfa_selection_titles.title_id = vfa_title_docs.title_id) ' .
			'AND (vfa_title_docs.doc_id= ?) ' . 'ORDER BY vfa_selections.name';
		return $this->findMany($sql, $pDocId);
	}
}

class row_selection extends abstract_row
{

	protected $sClassModel = 'model_selection';

	protected $tMessages = null;

	/**
	 * @return array row_title|null
	 */
	public function findTitles()
	{
		$tArray = null;
		if (null != $this->selection_id) {
			$tArray = model_title::getInstance()->findAllBySelectionId($this->selection_id);
		}
		return $tArray;
	}

	public function getSelectTitles()
	{
		$tArray = null;
		if (null != $this->selection_id) {
			$tArray = model_title::getInstance()->getSelectBySelectionId($this->selection_id);
		}
		return $tArray;
	}

	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		$oPluginValid->isNotEmpty('name');
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
		return $this->name . ' ' . $this->year;
	}
}
