<?php

class model_vote_result extends abstract_model
{

	protected $sClassRow = 'row_vote_result';

	protected $sTable = 'vfa_vote_results';

	protected $sConfig = 'mysql';

	protected $tId = array('vote_result_id');


	/**
	 * @return model_vote_result
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId string
	 * @return row_vote_result
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE vote_result_id=?', $uId);
	}

	/**
	 * @return row_vote_result[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	public function calcResultVotes($pIdAward)
	{
		$sql = 'SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score)' . ' FROM vfa_votes, vfa_vote_items' .
			' WHERE (vfa_votes.award_id = 38)' . ' AND (vfa_votes.number > 3)' . ' AND (vfa_votes.vote_id = vfa_vote_items.vote_id)' .
			' AND (vfa_vote_items.score > -1)' . ' GROUP BY vfa_vote_items.title_id';
		$res = $this->execute($sql);
		var_dump($res);
		while ($row = mysql_fetch_row($res)) {
			//var_dump($row);
			printf("TITLE_ID : %d,  Count : %d,  Sum : %d, Moy : %f </br>", $row[0], $row[1], $row[2], $row[2]/$row[1]);
		}
		mysql_free_result($res);
	}

}

class row_vote_result extends abstract_row
{
	// Mémo
	// vote_result_id, INT(11)
	// award_id, INT(11)
	// title_id, INT(11)
	// score, INT(11)
	// number, INT(11)
	// average, DECIMAL(10,5)
	// created, DATETIME
	// modified, DATETIME

	protected $sClassModel = 'model_vote_result';

	protected $tMessages = null;


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
}
