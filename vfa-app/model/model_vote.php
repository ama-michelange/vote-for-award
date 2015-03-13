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
	 * Trouve le dernier enregistrement modifié (dans le temps)
	 * @param int $pAwardId
	 * @return row_vote
	 */
	public function findLastModifiedByAwardId($pAwardId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE award_id=? ORDER BY modified DESC', $pAwardId);
	}

	/**
	 * @return row_vote[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	/**
	 * @param $pAwardId
	 * @param $pGroupId
	 * @return int
	 */
	public function countAllBallots($pAwardId, $pGroupId = null)
	{
		$ret = 0;
		if (null == $pGroupId) {
			$sql = 'SELECT count(*) FROM ' . $this->sTable . ' WHERE (award_id = ?)';
			$res = $this->execute($sql, $pAwardId);
		} else {
			$sql = 'SELECT count(*) FROM vfa_votes, vfa_user_groups' . ' WHERE (vfa_votes.award_id = ?)' .
				' AND (vfa_votes.user_id = vfa_user_groups.user_id) AND (vfa_user_groups.group_id = ?)';
			$res = $this->execute($sql, $pAwardId, $pGroupId);
		}
		while ($row = mysql_fetch_row($res)) {
			$ret = $row[0];
		}
		mysql_free_result($res);
		return $ret;
	}


	/**
	 * @param $pAwardId
	 * @param $pAwardType
	 * @return int
	 */
	public function countValidBallots($pAwardId, $pAwardType, $pGroupId = null)
	{
		$ret = 0;
		$min = plugin_vfa::MIN_NB_VOTE_AWARD_READER;
		IF ($pAwardType == plugin_vfa::TYPE_AWARD_BOARD) {
			$min = plugin_vfa::MIN_NB_VOTE_AWARD_BOARD;
		}
		if (null == $pGroupId) {
			$sql = 'SELECT count(*) FROM vfa_votes WHERE(award_id = ?) AND (number >= ' . $min . ')';
			$res = $this->execute($sql, $pAwardId);
		} else {
			$sql = 'SELECT count(*) FROM vfa_votes, vfa_user_groups WHERE(vfa_votes.award_id = ?) AND (vfa_votes.number >= ' . $min . ')' .
				' AND (vfa_votes.user_id = vfa_user_groups.user_id) AND (vfa_user_groups.group_id = ?)';
			$res = $this->execute($sql, $pAwardId, $pGroupId);
		}
		while ($row = mysql_fetch_row($res)) {
			$ret = $row[0];
		}
		mysql_free_result($res);
		return $ret;
	}

	public function minUserId()
	{
		$ret = -1;
		$row = $this->findOne('SELECT min(user_id) FROM ' . $this->sTable);
		if (false == $row->isEmpty()) {
			$ret = $row->__get('min(user_id)');
		}
		return $ret;
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
