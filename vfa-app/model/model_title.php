<?php

class model_title extends abstract_model
{

	protected $sClassRow = 'row_title';

	protected $sTable = 'vfa_titles';

	protected $sConfig = 'mysql';

	protected $tId = array('title_id');

	/**
	 * @return model_title
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_title
	 */

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE title_id=?', $uId);
	}

	/**
	 * @param $uTitle
	 * @param $uNumbers
	 * @return row_title[]
	 */
	public function findByTitleAndNumbers($uTitle, $uNumbers)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE title=? AND numbers=?', $uTitle, $uNumbers);
	}

	/**
	 * @return row_title[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY order_title');
	}

	/**
	 * @param $pSelectionId string
	 * @return row_title[]
	 */
	public function findAllBySelectionId($pSelectionId)
	{
		$sql = 'SELECT * FROM vfa_titles, vfa_selection_titles ' . 'WHERE (vfa_selection_titles.title_id = vfa_titles.title_id) ' .
			'AND (vfa_selection_titles.selection_id = ?) ' . 'ORDER BY vfa_titles.order_title';
		return $this->findMany($sql, $pSelectionId);
	}

	/**
	 * @return row_title[]
	 */
	public function findAllByDocId($pDocId)
	{
		$sql = 'SELECT * FROM vfa_titles, vfa_title_docs ' . 'WHERE (vfa_title_docs.title_id = vfa_titles.title_id) ' .
			'AND (vfa_title_docs.doc_id= ?) ' . 'ORDER BY vfa_titles.order_title';
		return $this->findMany($sql, $pDocId);
	}

	/**
	 * @return row_title[]
	 */
	public function findByDocIdSelectionId($pDocId, $pSelectionId)
	{
		$sql = 'SELECT DISTINCT * FROM vfa_titles, vfa_selection_titles, vfa_title_docs ' .
			'WHERE (vfa_title_docs.title_id = vfa_titles.title_id) ' .
			'AND (vfa_title_docs.doc_id= ?) ' . 'AND (vfa_selection_titles.selection_id= ?) ';
		return $this->findOne($sql, $pDocId, $pSelectionId);
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			if (null == $oRow->numbers) {
				$tSelect[$oRow->title_id] = $oRow->title;
			} else {
				$tSelect[$oRow->title_id] = $oRow->title . ' (' . $oRow->numbers . ')';
			}
		}
		return $tSelect;
	}

	public function getSelectBySelectionId($pSelectionId)
	{
		$tab = $this->findAllBySelectionId($pSelectionId);
		$tSelect = array();
		foreach ($tab as $oRow) {
			if (null == $oRow->numbers) {
				$tSelect[$oRow->title_id] = $oRow->title;
			} else {
				$tSelect[$oRow->title_id] = $oRow->title . ' (' . $oRow->numbers . ')';
			}
		}
		return $tSelect;
	}

	public function deleteTitleDocs($pIdTitle)
	{
		$this->execute('DELETE FROM vfa_title_docs WHERE title_id=?', $pIdTitle);
	}

	public function saveTitleDocs($pIdTitle, $pIdDocs)
	{
		$this->deleteTitleDocs($pIdTitle);
		foreach ($pIdDocs as $idDoc) {
			$this->execute('INSERT INTO vfa_title_docs (title_id, doc_id) VALUES (?,?)', $pIdTitle, $idDoc);
		}
	}

	public function deleteSelectionTitle($pIdSelection, $pIdTitle)
	{
		$this->execute('DELETE FROM vfa_selection_titles WHERE selection_id=? AND title_id=?', $pIdSelection, $pIdTitle);
	}

	public function saveSelectionTitle($pIdSelection, $pIdTitle)
	{
		$find = $this->findOne('SELECT * FROM vfa_selection_titles WHERE selection_id=? AND title_id=?', $pIdSelection, $pIdTitle);
		if (true == $find->isEmpty()) {
			$this->execute('INSERT INTO vfa_selection_titles (selection_id, title_id) VALUES (?, ?)', $pIdSelection, $pIdTitle);
		}
	}
}

class row_title extends abstract_row
{

	protected $sClassModel = 'model_title';

	protected $tMessages = null;

	public function findDocs()
	{
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_doc::getInstance()->findAllByTitleId($this->title_id);
		}
		return $tArray;
	}

	public function findSelections()
	{
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_selection::getInstance()->findAllByTitleId($this->title_id);
		}
		return $tArray;
	}

	public function getSelectDocs()
	{
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_doc::getInstance()->getSelectByTitleId($this->title_id);
		}
		return $tArray;
	}

	public function toString()
	{
		$name = $this->title;
		if (null != $this->numbers) {
			$name .= ' (' . $this->numbers . ')';
		}
		return $name;
	}

	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('title');
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
