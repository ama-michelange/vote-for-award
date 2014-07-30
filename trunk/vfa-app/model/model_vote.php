<?php

class model_vote extends abstract_model
{

	protected $sClassRow = 'row_vote';

	protected $sTable = 'vfa_votes';

	protected $sConfig = 'mysql';

	protected $tId = array('vote_id');


	/**
	 * @return model_vote
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param string $uId
	 * @return row_vote
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE vote_id=?', $uId);
	}

	/**
	 * @param int $pUserId
	 * @param int $pAwardId
	 * @return row_vote
	 */
	public function findByUserIdAwardId($pUserId, $pAwardId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE user_id=? AND award_id=?', $pUserId, $pAwardId);
	}

	/**
	 * @return row_vote[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

}

class row_vote extends abstract_row
{
	// Mémo
	// vote_id, INT(11)
	// award_id, INT(11)
	// user_id, INT(11)
	// number, TINYINT(4)
	// average, DECIMAL(10,5)
	// created, DATETIME

	protected $sClassModel = 'model_vote';

	protected $tMessages = null;



	private $toVoteItems = null;


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
	 * @return row_vote_item[]|null
	 */
	public function getVoteItems()
	{
		return $this->toVoteItems;
	}

	/**
	 * @param row_vote_item[] $ptoVoteItems
	 */
	public function setVoteItems($ptoVoteItems)
	{
		$this->toVoteItems = $ptoVoteItems;
	}
}
