<?php

/**
 * Class model_vote_result
 */
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

	/**
	 * @param $pIdAward string
	 * @return row_vote_result[]
	 */
	public function findAllByIdAward($pIdAward)
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' WHERE award_id=? ORDER BY average DESC', $pIdAward);
	}

	/**
	 * @param $poAward row_award
	 * @return row_vote_result[]
	 */
	public function calcResultVotes($poAward)
	{
		if (!isset($poAward) || $poAward->isEmpty()) {
			return null;
		}

		$idAward = $poAward->award_id;
		$minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_BD;
		if ($poAward->type == plugin_vfa::TYPE_AWARD_BOARD) {
			$minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_BOARD;
		} else {
            if ($poAward->getCategory() == plugin_vfa::CATEGORY_AWARD_LIVRE) {
                $minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_LIVRE;
         	}
        }

		$sql = 'SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score) FROM vfa_votes, vfa_vote_items' .
			' WHERE (vfa_votes.award_id = ' . $idAward . ') AND (vfa_votes.number >= ' . $minNbVote . ')' .
			' AND (vfa_votes.vote_id = vfa_vote_items.vote_id) AND (vfa_vote_items.score > -1)' . ' GROUP BY vfa_vote_items.title_id';
		//	var_dump($sql);
		$res = $this->execute($sql);

		$toVoteResults = array();
		while ($row = mysql_fetch_row($res)) {
			// var_dump($row);
			//	printf("TITLE_ID : %d,  Count : %d,  Sum : %d, Moy : %f </br>", $row[0], $row[1], $row[2], $row[2] / $row[1]);
			$oVoteResult = new row_vote_result();
			$oVoteResult->award_id = $idAward;
			$oVoteResult->title_id = $row[0];
			$oVoteResult->number = $row[1];
			$oVoteResult->score = $row[2];
			$oVoteResult->average = $row[2] / $row[1];
			$oVoteResult->modified = plugin_vfa::dateTimeSgbd();
			$toVoteResults[] = $oVoteResult;
		}
		mysql_free_result($res);

		return $toVoteResults;
	}

	/**
	 * @param $poAward row_award
	 * @param $poGroup row_group
	 * @return row_vote_result[]
	 */
	public function calcResultGroupVotes($poAward, $poGroup)
	{
		if (!isset($poAward) || $poAward->isEmpty()) {
			return null;
		}
		$idAward = $poAward->award_id;
		if (!isset($poGroup) || $poGroup->isEmpty()) {
			return null;
		}
		$idGroup = $poGroup->group_id;

		$minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_BD;
		if ($poAward->type == plugin_vfa::TYPE_AWARD_BOARD) {
			$minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_BOARD;
		} else {
            if ($poAward->getCategory() == plugin_vfa::CATEGORY_AWARD_LIVRE) {
                $minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_LIVRE;
         	}
        }

		$sql = 'SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score) FROM vfa_votes, vfa_vote_items, vfa_user_groups' .
			' WHERE (vfa_votes.award_id = ' . $idAward . ') AND (vfa_votes.number >= ' . $minNbVote . ')' .
			' AND (vfa_votes.vote_id = vfa_vote_items.vote_id) AND (vfa_vote_items.score > -1)' .
			' AND (vfa_votes.user_id = vfa_user_groups.user_id) AND (vfa_user_groups.group_id = ' . $idGroup . ')' .
			' GROUP BY vfa_vote_items.title_id';
		// var_dump($sql);
		$res = $this->execute($sql);

		$toVoteResults = array();
		while ($row = mysql_fetch_row($res)) {
			// var_dump($row);
			//	printf("TITLE_ID : %d,  Count : %d,  Sum : %d, Moy : %f </br>", $row[0], $row[1], $row[2], $row[2] / $row[1]);
			$oVoteResult = new row_vote_result();
			$oVoteResult->award_id = $idAward;
			$oVoteResult->title_id = $row[0];
			$oVoteResult->number = $row[1];
			$oVoteResult->score = $row[2];
			$oVoteResult->average = $row[2] / $row[1];
			$oVoteResult->modified = plugin_vfa::dateTimeSgbd();
			$toVoteResults[] = $oVoteResult;
		}
		mysql_free_result($res);

		return $toVoteResults;
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

	/**
	 * @return row_title
	 */
	public function findTitle()
	{
		$oTitle = model_title::getInstance()->findById(($this->title_id));
		if (null == $oTitle) {
			$oTitle = new row_title();
		}
		return $oTitle;
	}

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

	static function cmpAverageDesc($a, $b)
	{
		if ($a->average == $b->average) {
			return 0;
		}
		return ($a->average > $b->average) ? -1 : +1;
	}
}
