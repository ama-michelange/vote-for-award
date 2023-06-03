<?php

class model_stats extends abstract_model
{

    protected $sClassRow = 'row_stats_ranking';

    protected $sTable = 'vfa_awards';

    protected $sConfig = 'mysql';

    protected $tId = array('award_id');

    const RANKING = "RANKING";
    const PARTICIPATION = "PARTICIPATION";
    const REGISTERED = "REGISTERED";
    const BALLOTS = "BALLOTS";
    const VALID_BALLOTS = "VALID_BALLOTS";
    const GENDER_ALL = "GENDER_ALL";
    const GENDER_MALE = "GENDER_MALE";
    const GENDER_FEMALE = "GENDER_FEMALE";
    const GENDER_UNKNOWN = "GENDER_UNKNOWN";
    const AGE_ALL = "AGE_ALL";
    const AGE_UNDER_20 = "AGE_UNDER_20";
    const AGE_BETWEEN_20_30 = "AGE_BETWEEN_20_30";
    const AGE_BETWEEN_30_40 = "AGE_BETWEEN_30_40";
    const AGE_BETWEEN_40_50 = "AGE_BETWEEN_40_50";
    const AGE_BETWEEN_50_60 = "AGE_BETWEEN_50_60";
    const AGE_OVER_60 = "AGE_OVER_60";
    const AGE_UNKNOWN = "AGE_UNKNOWN";

    /**
     * @return model_stats
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

    /**
     * @param $poAward row_award
     * @return row_stats[]
     */
    public function calcAllStatistics($poAward)
    {
        return array(
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward)),
            new row_stats(self::RANKING, $this->calcRanking($poAward)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_MALE)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_MALE)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_FEMALE)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_FEMALE)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_UNKNOWN)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_UNKNOWN)),

            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_UNDER_20)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_UNDER_20)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_BETWEEN_20_30)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_BETWEEN_20_30)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_BETWEEN_30_40)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_BETWEEN_30_40)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_BETWEEN_40_50)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_BETWEEN_40_50)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_BETWEEN_50_60)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_BETWEEN_50_60)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_OVER_60)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_OVER_60)),
            new row_stats(self::PARTICIPATION, $this->calcParticipation($poAward, self::GENDER_ALL, self::AGE_UNKNOWN)),
            new row_stats(self::RANKING, $this->calcRanking($poAward, self::GENDER_ALL, self::AGE_UNKNOWN)),
        );
    }

    /**
     * @param $poAward row_award
     * @return row_stats_ranking[]
     */
    public function calcRanking($poAward, $gender = self::GENDER_ALL, $age = self::AGE_ALL)
    {
        if (!isset($poAward) || $poAward->isEmpty()) {
            return null;
        }

        $idAward = $poAward->award_id;
        $year = $poAward->year;
        $minNbVote = $this->buildMinNbVote($poAward);

        $andGenre = $this->buildAndGenre($gender);
        $andAge = $this->buildAndAge($age, $year);

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
            $oRank = new row_stats_ranking();
            $oRank->setType(self::RANKING);
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

    /**
     * @param $poAward row_award
     * @return row_stats_participation[]
     */
    public function calcParticipation($poAward, $gender = self::GENDER_ALL, $age = self::AGE_ALL)
    {
        if (!isset($poAward) || $poAward->isEmpty()) {
            return null;
        }

        $idAward = $poAward->award_id;
        $year = $poAward->year;

        $andGenre = $this->buildAndGenre($gender);
        $andAge = $this->buildAndAge($age, $year);

        // Registered
        $sql = "SELECT count(*)," . " AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_user_awards, vfa_users" .
            " WHERE vfa_user_awards.award_id = " . $idAward .
            $andGenre . $andAge .
            " AND (vfa_user_awards.user_id = vfa_users.user_id)";
        // echo "<br>"; var_dump($sql);
        $toArray[] = $this->exec_sql_participation($sql, self::REGISTERED, $gender, $age, $idAward);

        // Ballots count
        $sql = "SELECT count(*), AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_votes, vfa_users" .
            " WHERE vfa_votes.award_id = " . $idAward .
            " AND (vfa_votes.user_id = vfa_users.user_id)" .
            $andGenre . $andAge;
        $toArray[] = $this->exec_sql_participation($sql, self::BALLOTS, $gender, $age, $idAward);

        // Valid Ballots count
        $minNbVote = $this->buildMinNbVote($poAward);
        $sql = "SELECT count(*), AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_votes, vfa_users" .
            " WHERE vfa_votes.award_id = " . $idAward .
            ' AND (vfa_votes.number >= ' . $minNbVote . ')' .
            " AND (vfa_votes.user_id = vfa_users.user_id)" .
            $andGenre . $andAge;
        $toArray[] = $this->exec_sql_participation($sql, self::VALID_BALLOTS, $gender, $age, $idAward);
        return $toArray;
    }

    /**
     * @param $gender
     * @return string
     */
    public function buildAndGenre($gender)
    {
        $andGenre = "";
        if ($gender == self::GENDER_MALE) {
            $andGenre = " AND (vfa_users.gender = 'M')";
        } elseif ($gender == self::GENDER_FEMALE) {
            $andGenre = " AND (vfa_users.gender = 'F')";
        } elseif ($gender == self::GENDER_UNKNOWN) {
            $andGenre = " AND (vfa_users.gender IS NULL)";
        }
        return $andGenre;
    }

    /**
     * @param $age
     * @param $year
     * @return string
     */
    public function buildAndAge($age, $year)
    {
        $andAge = "";
        if ($age == self::AGE_UNDER_20) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) < 20)";
        } elseif ($age == self::AGE_BETWEEN_20_30) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) >= 20) AND ((" . $year . " - vfa_users.birthyear) < 30)";
        } elseif ($age == self::AGE_BETWEEN_30_40) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) >= 30) AND ((" . $year . " - vfa_users.birthyear) < 40)";
        } elseif ($age == self::AGE_BETWEEN_40_50) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) >= 40) AND ((" . $year . " - vfa_users.birthyear) < 50)";
        } elseif ($age == self::AGE_BETWEEN_50_60) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) >= 50) AND ((" . $year . " - vfa_users.birthyear) < 60)";
        } elseif ($age == self::AGE_OVER_60) {
            $andAge = " AND ((" . $year . " - vfa_users.birthyear) >= 60)";
        } elseif ($age == self::AGE_UNKNOWN) {
            $andAge = " AND (vfa_users.birthyear IS NULL)";
        }
        return $andAge;
    }

    /**
     * @param $sql
     * @param $gender
     * @param $age
     * @param $idAward
     * @return row_stats_participation
     */
    public function exec_sql_participation($sql, $cat, $gender, $age, $idAward)
    {
        // echo "<p>SQL</p>", "<p>"; var_dump($sql); echo "</p>";
        $res = $this->execute($sql);
        // echo "<p>RESULT</p>", "<p>"; var_dump($res); echo "</p>";

        $toArray = array();
        while ($row = mysql_fetch_row($res)) {
            $oItem = new row_stats_participation();
            $oItem->setType(self::PARTICIPATION);
            $oItem->setCategory($cat);
            $oItem->setGender($gender);
            $oItem->setAge($age);
            $oItem->setAward(model_award::getInstance()->findById($idAward));
            $oItem->setCount($row[0]);
            $oItem->setAverageAge(number_format($row[1], 1, ',', ''));
            $oItem->setMinAge($row[2]);
            $oItem->setMaxAge($row[3]);
            $toArray[] = $oItem;
        }
        mysql_free_result($res);
        return $toArray[0];
    }

    /**
     * @param row_award $poAward
     * @return int
     */
    public function buildMinNbVote(row_award $poAward)
    {
        $minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_BD;
        if ($poAward->type == plugin_vfa::TYPE_AWARD_BOARD) {
            $minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_BOARD;
        } else {
            if ($poAward->getCategory() == plugin_vfa::CATEGORY_AWARD_LIVRE) {
                $minNbVote = plugin_vfa::MIN_NB_VOTE_AWARD_READER_LIVRE;
            }
        }
        return $minNbVote;
    }
}

class row_stats
{
    public function __construct($type, $stats)
    {
        $this->setType($type);
        $this->setStats($stats);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getStats()
    {
        return $this->stats;
    }

    public function setStats($value)
    {
        $this->stats = $value;
    }
}

class row_stats_ranking
{
    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

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

class row_stats_participation
{
    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($value)
    {
        $this->category = $value;
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

    public function getAward()
    {
        return $this->award;
    }

    public function setAward($value)
    {
        $this->award = $value;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($value)
    {
        $this->count = $value;
    }

    public function getAverageAge()
    {
        return $this->average_age;
    }

    public function setAverageAge($value)
    {
        $this->average_age = $value;
    }

    public function getMinAge()
    {
        return $this->min_age;
    }

    public function setMinAge($value)
    {
        $this->min_age = $value;
    }

    public function getMaxAge()
    {
        return $this->max_age;
    }

    public function setMaxAge($value)
    {
        $this->max_age = $value;
    }

}
