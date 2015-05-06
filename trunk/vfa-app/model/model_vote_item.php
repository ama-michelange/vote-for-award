<?php

class model_vote_item extends abstract_model
{

	protected $sClassRow = 'row_vote_item';

	protected $sTable = 'vfa_vote_items';

	protected $sConfig = 'mysql';

	protected $tId = array('vote_item_id');


	/**
	 * @return model_vote_item
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param int $uId
	 * @return row_vote_item
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE vote_item_id=?', $uId);
	}

	/**
	 * @param int $pVoteId
	 * @param int $pTitleId
	 * @return row_vote_item
	 */
	public function findByVoteIdTitleId($pVoteId, $pTitleId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE vote_id=? AND title_id=?', $pVoteId, $pTitleId);
	}

	/**
	 * @return row_vote_item[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	/**
	 * @return row_vote_item[]
	 */
	public function findAllByVoteId($pVoteId)
	{
		return $this->findMany('SELECT * FROM vfa_vote_items, vfa_titles' .
			' WHERE vfa_vote_items.vote_id=? AND vfa_vote_items.title_id=vfa_titles.title_id' . ' ORDER BY vfa_titles.order_title', $pVoteId);
	}

}

class row_vote_item extends abstract_row
{

	// Mémo
	// vote_item_id, INT(11)
	// vote_id, INT(11)
	// title_id, INT(11)
	// score, TINYINT(4)
	// comment, TEXT
	// modified, DATETIME


	protected $sClassModel = 'model_vote_item';

	protected $tMessages = null;
	private $oTitle = null;


	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
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
	 * @return row_title|null
	 */
	public function getTitle()
	{
		return $this->oTitle;
	}

	/**
	 * @param row_title $poTitle
	 */
	public function setTitle($poTitle)
	{
		$this->oTitle = $poTitle;
	}

}
