<?php

class model_stats extends abstract_model
{

    protected $sClassRow = 'row_stat_ranking';

    protected $sTable = 'vfa_awards';

    protected $sConfig = 'mysql';

    protected $tId = array('award_id');

    const GENDER_ALL = "GENDER_ALL";
    const GENDER_MALE = "GENDER_MALE";
    const GENDER_FEMALE = "GENDER_FEMALE";
    const GENDER_UNKNOWN = "GENDER_UNKNOWN";
    const AGE_ALL = "AGE_ALL";
    const AGE_UNDER_20 = "AGE_UNDER_20";
    const AGE_BETWEEN_20_30 = "AGE_BETWEEN_20_30";
    const AGE_BETWEEN_30_40 = "AGE_BETWEEN_30_40";
    const AGE_BETWEEN_40_50 = "AGE_BETWEEN_40_50";
    const AGE_OVER_50 = "AGE_OVER_50";

    /**
     * @return model_stats
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }


    public function calcAllRanking($poAward)
    {
        return array(
            self::GENDER_ALL => $this->calcRanking($poAward),
            self::GENDER_MALE => $this->calcRanking($poAward, self::GENDER_MALE),
            self::GENDER_FEMALE => $this->calcRanking($poAward, self::GENDER_FEMALE),
            self::GENDER_UNKNOWN => $this->calcRanking($poAward, self::GENDER_UNKNOWN),
            self::AGE_UNDER_20 => $this->calcRanking($poAward,self::GENDER_ALL, self::AGE_UNDER_20),
            self::AGE_BETWEEN_20_30 => $this->calcRanking($poAward,self::GENDER_ALL, self::AGE_BETWEEN_20_30),
            self::AGE_BETWEEN_30_40 => $this->calcRanking($poAward,self::GENDER_ALL, self::AGE_BETWEEN_30_40),
            self::AGE_BETWEEN_40_50 => $this->calcRanking($poAward,self::GENDER_ALL, self::AGE_BETWEEN_40_50),
            self::AGE_OVER_50 => $this->calcRanking($poAward,self::GENDER_ALL, self::AGE_OVER_50),
        );
    }

    /**
     * @param $poAward row_award
     * @return row_stat_ranking[]
     */
    public function calcRanking($poAward, $gender = self::GENDER_ALL, $age = self::AGE_ALL)
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

        $andGenre = "";
        if ($gender == self::GENDER_MALE) {
            $andGenre = " AND (vfa_users.gender = 'M')";
        } elseif ($gender == self::GENDER_FEMALE) {
            $andGenre = " AND (vfa_users.gender = 'F')";
        } elseif ($gender == self::GENDER_UNKNOWN) {
            $andGenre = " AND (vfa_users.gender IS NULL)";
        }
        $andAge = "";
        if ($age == self::AGE_UNDER_20) {
            $andAge = " AND ((2023 - vfa_users.birthyear) < 20)";
        } elseif ($age == self::AGE_BETWEEN_20_30) {
            $andAge = " AND ((2023 - vfa_users.birthyear) >= 20) AND ((2023 - vfa_users.birthyear) < 30)";
        } elseif ($age == self::AGE_BETWEEN_30_40) {
            $andAge = " AND ((2023 - vfa_users.birthyear) >= 30) AND ((2023 - vfa_users.birthyear) < 40)";
        } elseif ($age == self::AGE_BETWEEN_40_50) {
            $andAge = " AND ((2023 - vfa_users.birthyear) >= 40) AND ((2023 - vfa_users.birthyear) < 50)";
        } elseif ($age == self::AGE_OVER_50) {
            $andAge = " AND ((2023 - vfa_users.birthyear) >= 50)";
        }

        $sql = 'SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score), FORMAT(sum(vfa_vote_items.score) / count(*),5) as ama_avg' .
            ' FROM vfa_votes, vfa_vote_items, vfa_users' .
            ' WHERE (vfa_votes.award_id = ' . $idAward . ')' .
            ' AND (vfa_votes.number >= ' . $minNbVote . ')' .
            ' AND (vfa_votes.vote_id = vfa_vote_items.vote_id) AND (vfa_vote_items.score > -1)' .
            ' AND (vfa_votes.user_id = vfa_users.user_id)' .
            $andGenre . $andAge .
            ' GROUP BY vfa_vote_items.title_id' .
            ' ORDER BY ama_avg DESC';
        // var_dump($sql);
        $res = $this->execute($sql);

        $toRanking = array();
        $rank = 0;
        while ($row = mysql_fetch_row($res)) {
            $rank++;
            // var_dump($row);
            //	printf("TITLE_ID : %d,  Count : %d,  Sum : %d, Moy : %f </br>", $row[0], $row[1], $row[2], $row[2] / $row[1]);
            $oRank = new row_stat_ranking();
            $oRank->setRank($rank);
            $oRank->setGender($gender);
            $oRank->setAge($age);
            $oRank->setAward(model_award::getInstance()->findById($idAward));
            $oRank->setTitle(model_title::getInstance()->findById($row[0]));
            $oRank->setNbVotes($row[1]);
            $oRank->setAverage(number_format($row[3], 5, ',', ''));
            $toRanking[] = $oRank;
        }
        mysql_free_result($res);

        return $toRanking;
    }

}

/**
 * Description
 * - rank
 * - title
 * - nb_votes
 * - average
 */
class row_stat_ranking
{
    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($value)
    {
        $this->rank = $value;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($value)
    {
        $this->gender = $value;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($value)
    {
        $this->age = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function getAward()
    {
        return $this->award;
    }

    public function setAward($value)
    {
        $this->award = $value;
    }

    public function getNbVotes()
    {
        return $this->nb_votes;
    }

    public function setNbVotes($value)
    {
        $this->nb_votes = $value;
    }

    public function getAverage()
    {
        return $this->average;
    }

    public function setAverage($value)
    {
        $this->average = $value;
    }

}
