<?php

class model_stats extends abstract_model
{

    protected $sClassRow = 'RowStatistics';

    protected $sTable = 'vfa_awards';

    protected $sConfig = 'mysql';

    protected $tId = array('award_id');

    const RANKING = "RANKING";
    const PARTICIPATION = "PARTICIPATION";
    const ALL_GROUPS = "ALL_GROUPS";
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
     * @return RowStatistics[]
     */
    public function calcAllStatistics($poAward)
    {
        $oAwardForStat = new AwardForStatistic($poAward, $this->findAllGroupsByAwardsWithRegisteredUsers($poAward->getId()));

        $ret = array();
        // Global stats
        $ret = array_merge($ret, $this->calcStatistics($oAwardForStat));
        // Groups stats
//        foreach ($oAwardForStat->getGroups() as $oGroup) {
//            $ret = array_merge($ret, $this->calcStatistics($oAwardForStat, $oGroup));
//        }

        return $ret;
    }

    /**
     * @param AwardForStatistic $oAwardForStat
     * @return array
     */
    public function calcStatistics(AwardForStatistic $oAwardForStat, $poGroup = null)
    {
        return array(
            // Global
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipationGroups($oAwardForStat)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_ALL, $poGroup)),

            // By Gender
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_MALE, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_MALE, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_FEMALE, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_FEMALE, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_UNKNOWN, self::AGE_ALL, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_UNKNOWN, self::AGE_ALL, $poGroup)),
            // By Age
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_UNDER_20, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_UNDER_20, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_20_30, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_20_30, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_30_40, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_30_40, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_40_50, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_40_50, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_50_60, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_BETWEEN_50_60, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_OVER_60, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_OVER_60, $poGroup)),
            new RowStatistics(self::PARTICIPATION, $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_UNKNOWN, $poGroup)),
            new RowStatistics(self::RANKING, $this->calcRanking($oAwardForStat, self::GENDER_ALL, self::AGE_UNKNOWN, $poGroup)),
        );
    }

    public function calcParticipationGroups(AwardForStatistic $oAwardForStat)
    {
        $retGroups = array();
        foreach ($oAwardForStat->getGroups() as $oGroup) {
            $arrayGroups = $this->calcParticipation($oAwardForStat, self::GENDER_ALL, self::AGE_ALL, $oGroup);
            $retGroups = array_merge($retGroups, $arrayGroups);
        }

//        var_dump($ret);
//        echo "<br> COUNT retGroups: ", count($retGroups);

        return $retGroups;
    }

    /**
     * @param $poAwardForStat AwardForStatistic
     * @return RowRankingStatistic[]
     */
    public function calcRanking($poAwardForStat, $gender = self::GENDER_ALL, $age = self::AGE_ALL, $poGroup = null)
    {
        $oAward = $poAwardForStat->getAward();
        if (!isset($oAward) || $oAward->isEmpty()) {
            return null;
        }
        $idAward = $oAward->award_id;
        $year = $oAward->year;
        $minNbVote = $this->buildMinNbVote($oAward);

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
            $oRank = new RowRankingStatistic();
            $oRank->setType(self::RANKING);
            $oRank->setRank($rank);
            $oRank->setGender($gender);
            $oRank->setAge($age);
            $oRank->setAward(model_award::getInstance()->findById($idAward));
            $oRank->setGroup($poGroup);
            $oRank->setTitle(model_title::getInstance()->findById($row[0]));
            $oRank->setNbVotes($row[1]);
            $oRank->setAverage(number_format($row[3], 5, ',', ''));
            $toRanking[] = $oRank;
        }
        mysql_free_result($res);

        return $toRanking;
    }


    /**
     * @param $poAwardForStat AwardForStatistic
     * @return RowParticipationStatistic[]
     */
    public function calcParticipation($poAwardForStat, $gender = self::GENDER_ALL, $age = self::AGE_ALL, $poGroup = null)
    {
        $oAward = $poAwardForStat->getAward();
        if (!isset($oAward) || $oAward->isEmpty()) {
            return null;
        }
        $idAward = $oAward->award_id;
        $year = $oAward->year;

        $andGenre = $this->buildAndGenre($gender);
        $andAge = $this->buildAndAge($age, $year);

        $fromGroup = "";
        $andGroup = "";
        if ($poGroup != null) {
            $fromGroup = ", vfa_user_groups";
            $andGroup = " AND (vfa_user_groups.user_id = vfa_users.user_id) AND vfa_user_groups.group_id = " . $poGroup->getId();
        }


        // Registered
        $sql = "SELECT count(*)," . " AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_user_awards, vfa_users" . $fromGroup .
            " WHERE vfa_user_awards.award_id = " . $idAward .
            " AND (vfa_user_awards.user_id = vfa_users.user_id)".
            $andGenre . $andAge . $andGroup;
        $toArray[] = $this->exec_sql_participation($sql, self::REGISTERED, $gender, $age, $poAwardForStat, $poGroup);

        // Ballots count
        $sql = "SELECT count(*), AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_votes, vfa_users" . $fromGroup .
            " WHERE vfa_votes.award_id = " . $idAward .
            " AND (vfa_votes.user_id = vfa_users.user_id)" .
            $andGenre . $andAge . $andGroup;
        $toArray[] = $this->exec_sql_participation($sql, self::BALLOTS, $gender, $age, $poAwardForStat, $poGroup);

        // Valid Ballots count
        $minNbVote = $this->buildMinNbVote($oAward);
        $sql = "SELECT count(*), AVG(" . $year . " - vfa_users.birthyear)," .
            " MIN(" . $year . " - vfa_users.birthyear)," . " MAX(" . $year . " - vfa_users.birthyear)" .
            " FROM vfa_votes, vfa_users" .$fromGroup.
            " WHERE vfa_votes.award_id = " . $idAward .
            ' AND (vfa_votes.number >= ' . $minNbVote . ')' .
            " AND (vfa_votes.user_id = vfa_users.user_id)" .
            $andGenre . $andAge. $andGroup;
        $toArray[] = $this->exec_sql_participation($sql, self::VALID_BALLOTS, $gender, $age, $poAwardForStat, $poGroup);
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
     * @param $sql string
     * @param $gender string
     * @param $age int
     * @param $poAwardForStat AwardForStatistic
     * @param $poGroup row_group
     * @return RowParticipationStatistic
     */
    public function exec_sql_participation($sql, $cat, $gender, $age, $poAwardForStat, $poGroup = null)
    {
        // echo "<p>SQL</p>", "<p>"; var_dump($sql); echo "</p>";
        $res = $this->execute($sql);
        // echo "<p>RESULT</p>", "<p>"; var_dump($res); echo "</p>";

//        echo "<br>exec_sql_participation A";
        $toArray = array();
        while ($row = mysql_fetch_row($res)) {
            $oItem = new RowParticipationStatistic();
            $oItem->setType(self::PARTICIPATION);
            $oItem->setCategory($cat);
            $oItem->setGender($gender);
            $oItem->setAge($age);
            $oItem->setAward($poAwardForStat->getAward());
            $oItem->setGroup($poGroup);
            $oItem->setGroupsCount($poAwardForStat->getGroupsCount());
            $oItem->setUsersCount($row[0]);
            $oItem->setAverageAge(number_format($row[1], 1, ',', ''));
            $oItem->setMinAge($row[2]);
            $oItem->setMaxAge($row[3]);
            $toArray[] = $oItem;
        }
        mysql_free_result($res);
//        echo "<br>exec_sql_participation B";
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

    /**
     * @param $pIdAward
     * @return row_group[]
     */
    public function findAllGroupsByAwardsWithRegisteredUsers($pIdAward)
    {
        $sql = "SELECT DISTINCT vfa_user_groups.group_id" .
            " FROM vfa_user_groups, vfa_user_awards, vfa_groups" .
            " WHERE vfa_user_awards.award_id = " . $pIdAward .
            " AND (vfa_user_awards.user_id = vfa_user_groups.user_id)" .
            " AND (vfa_user_groups.group_id = vfa_groups.group_id)" .
            // Uniquement les groupes de rôle READER
            " AND (vfa_groups.role_id_default = 7)" .
            " ORDER BY vfa_groups.group_name";
        $res = $this->execute($sql);

        $tGroupId = array();
        while ($row = mysql_fetch_row($res)) {
            $tGroupId[] = $row[0];
        }
        mysql_free_result($res);

        $toGroups = array();

        foreach ($tGroupId as $groupId) {
            $oGroup = model_group::getInstance()->findById($groupId);
            $toGroups[] = $oGroup;
        }

        return $toGroups;
    }

}

class AwardForStatistic
{
    public function __construct($poAward, $ptoGroup)
    {
        $this->setAward($poAward);
        $this->setGroups($ptoGroup);
    }

    public function getAward()
    {
        return $this->award;
    }

    public function setAward($value)
    {
        $this->award = $value;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($value)
    {
        $this->groups = $value;
        if (!isset($this->groups)) {
            $this->groups = array();
        }
    }

    public function getGroupsCount()
    {
        return count($this->getGroups());
    }


}

class RowStatistics extends RowTyped
{
    public function __construct($type, $stats)
    {
        $this->setType($type);
        $this->setStats($stats);
    }

    /**
     * @return RowParticipationStatistic|RowRankingStatistic
     */
    public function getStats()
    {
        return $this->stats;
    }

    public function setStats($value)
    {
        $this->stats = $value;
    }
}

class RowTyped
{
    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
    }
}

class RowCommonStatistic extends RowTyped
{
    public function getAward()
    {
        return $this->award;
    }

    public function setAward($value)
    {
        $this->award = $value;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($value)
    {
        $this->group = $value;
    }

    public function getGroupId()
    {
        $ret = model_stats::ALL_GROUPS;
        if ($this->group != null) {
            $ret = $this->getGroup()->getId();
        }
        return $ret;
    }

    public function getGroupName()
    {
        $ret = "";
        if ($this->group != null) {
            $ret = $this->getGroup()->toString();
        }
        return $ret;
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
}

class RowRankingStatistic extends RowCommonStatistic
{
    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($value)
    {
        $this->rank = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
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

class RowParticipationStatistic extends RowCommonStatistic
{
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($value)
    {
        $this->category = $value;
    }

    public function getUsersCount()
    {
        return $this->users_count;
    }

    public function setUsersCount($value)
    {
        $this->users_count = $value;
    }

    public function getGroupsCount()
    {
        return $this->groups_count;
    }

    public function setGroupsCount($value)
    {
        $this->groups_count = $value;
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
