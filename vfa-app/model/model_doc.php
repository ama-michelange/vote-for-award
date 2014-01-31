<?php

class model_doc extends abstract_model
{

	protected $sClassRow = 'row_doc';

	protected $sTable = 'vfa_docs';

	protected $sConfig = 'mysql';

	protected $tId = array(
		'doc_id'
	);

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_doc
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE doc_id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY order_title,LENGTH(number),number,proper_title');
	}

	public function findAllRecent()
	{
		$date = new plugin_date(date('Y-m-d'), 'Y-m-d');
		$date->addYear(- 2);
		return $this->findMany(
			'SELECT * FROM ' . $this->sTable . ' WHERE date_legal >= DATE(\'' . $date->toString() .
				 '\') ORDER BY order_title,LENGTH(number),number,proper_title');
	}

	public function findAllByTitleId($pTitleId)
	{
		$sql = 'SELECT * FROM vfa_docs, vfa_title_docs ' . 'WHERE (vfa_title_docs.doc_id = vfa_docs.doc_id) ' .
			 'AND (vfa_title_docs.title_id = ?) ' . 'ORDER BY vfa_docs.order_title,LENGTH(number),number,proper_title';
		return $this->findMany($sql, $pTitleId);
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->doc_id] = $oRow->toString();
		}
		return $tSelect;
	}

	public function getSelectRecent()
	{
		$tab = $this->findAllRecent();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->doc_id] = $oRow->toString();
		}
		return $tSelect;
	}

	public function getSelectByTitleId($pTitleId)
	{
		$tab = $this->findAllByTitleId($pTitleId);
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->doc_id] = $oRow->toString();
		}
		return $tSelect;
	}
}

class row_doc extends abstract_row
{

	protected $sClassModel = 'model_doc';

	protected $tMessages = null;

	public function findTitles()
	{
		$tArray = null;
		if (null != $this->doc_id) {
			$tArray = model_title::getInstance()->findAllByDocId($this->doc_id);
		}
		return $tArray;
	}
	
	/* validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('title');
		// $oPluginValid->matchExpression('number','/[0-9]/');
		// $oPluginValid->isEqual('number','2');
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

	/**
	 * Formate un enregistrement de document pour l'affichage sur une ligne.
	 *
	 * @return string Document formaté
	 */
	public function toString()
	{
		$s = $this->title;
		if ($this->number) {
			$s .= ' #' . $this->number;
		}
		if ($this->proper_title) {
			$s .= ' - ' . $this->proper_title;
		}
		return $s;
	}

	/**
	 * Formate un enregistrement de document pour l'affichage sur une ligne du titre et de son numéro s'il existe.
	 *
	 * @return string Document formaté
	 */
	public function toStringNumber()
	{
		$s = $this->title;
		if ($this->number) {
			$s .= ' #' . $this->number;
		}
		return $s;
	}

	/**
	 * Formate un enregistrement de document pour l'affichage sur une ligne de son numéro de série et de son titre propre.
	 *
	 * @return string Document formaté
	 */
	public function toStringNumberProperTitle()
	{
		$s = '';
		if ($this->number) {
			$s .= ' #' . $this->number;
		}
		if ($this->proper_title) {
			$s .= ' - ' . $this->proper_title;
		}
		return $s;
	}
}
