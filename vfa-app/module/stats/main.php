<?php

class module_stats extends abstract_module
{
    public function before()
    {
        _root::getAuth()->enable();
        $this->oLayout = new _layout('tpl_bs_bar');
    }

    public function after()
    {
        $this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
        $this->oLayout->show();
    }

    public function _index()
    {
        $html = $this->buildRankingStats();


        $oView = new _view('stats::list');
        $oView->title = 'Tous les utilisateurs';
        $oView->showHtml = $html;
        $this->oLayout->add('work', $oView);
    }

    public function _ama()
    {
        echo "<h1>Ama Statistiques</h1>";

        $awardId = _root::getParam('award_id');
        if (null == $awardId) {
            echo "<p>Pas de prix !</p>";
        } else {
            $oAward = model_award::getInstance()->findById($awardId);
            if ($oAward != null) {
                echo "<p>";
                echo $oAward->toString();
                echo "</p>";
            }

        }
        echo "<p>TERMINER</p>";
    }

    /**
     * @return string
     */
    private function buildRankingStats()
    {
        $ret = "";
//        echo '<!DOCTYPE html>';
//        echo '<html>';
//        echo '<head><title>Statistiques</title><meta charset="utf-8"></head>';
//        echo '<body>';

        $ret .= "<h1>Statistiques</h1>";

        $year = _root::getParam('year');
        if (null == $year) {
            $ret .= "<p>Pas d'année !</p>";
        } else {
            $order = 0;
            $toAwards = model_award::getInstance()->findAllAwardsByYear($year);
            foreach ($toAwards as $oAward) {
                $ret .= "<h2>" . $oAward->toString() . "</h2>";
                $toAllStats = model_stats::getInstance()->calcAllStatistics($oAward);

                foreach ($toAllStats as $toStats) {
                    if ($toStats->getType() == model_stats::PARTICIPATION) {
                        $ret .= '<table class="table table-striped table-hover">' . "<thead><tr>";
                        $ret .= "<th>order</th>" . "<th>type</th>" . "<th>award_id</th>" . "<th>award_title</th>" .
                            "<th>gender</th>" . "<th>age</th>" . "<th>category</th>" . "<th>count</th>" .
                            "<th>average_age</th>" . "<th>min_age</th>" . "<th>max_age</th>";
                        $ret .= "</tr></thead>" . "<tbody>";
                        foreach ($toStats->getStats() as $oParticipation) {
                            $order++;
                            $ret .= "<tr>";
                            $ret .= "<td>" . $order . "</td>";
                            $ret .= "<td>participation</td>";
                            $ret .= "<td>" . $oParticipation->getAward()->getId() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAward()->toString() . "</td>";
                            $ret .= "<td>" . $oParticipation->getGender() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getCategory() . "</td>";
                            $ret .= "<td>" . $oParticipation->getCount() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAverageAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getMinAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getMaxAge() . "</td>";
                            $ret .= "</tr>";
                        }
                        $ret .= "</tbody>";
                        $ret .= "</table>";
                    } else {
                        $ret .= '<table class="table table-striped table-hover">' . "<thead><tr>";
                        $ret .= "<th>order</th>" . "<th>type</th>" . "<th>award_id</th>" . "<th>award_title</th>" .
                            "<th>gender</th>" . "<th>age</th>" . "<th>title_id</th>" . "<th>rang</th>" .
                            "<th>title</th>" . "<th>nb_votes</th>" . "<th>average_votes</th>";
                        $ret .= "</tr></thead>" . "<tbody>";
                        foreach ($toStats->getStats() as $oRank) {
                            $order++;
                            $ret .= "<tr>";
                            $ret .= "<td>" . $order . "</td>";
                            $ret .= "<td>ranking</td>";
                            $ret .= "<td>" . $oRank->getAward()->getId() . "</td>";
                            $ret .= "<td>" . $oRank->getAward()->toString() . "</td>";
                            $ret .= "<td>" . $oRank->getGender() . "</td>";
                            $ret .= "<td>" . $oRank->getAge() . "</td>";
                            $ret .= "<td>" . $oRank->getTitle()->getId() . "</td>";
                            $ret .= "<td>" . $oRank->getRank() . "</td>";
                            $ret .= "<td>" . $oRank->getTitle()->toString() . "</td>";
                            $ret .= "<td>" . $oRank->getNbVotes() . "</td>";
                            $ret .= "<td>" . $oRank->getAverage() . "</td>";
                            $ret .= "</tr>";
                        }
                        $ret .= "</tbody>";
                        $ret .= "</table>";
                    }
                }
            }

        }
        $ret .= '</body></html>';
        return $ret;
    }

    private function echoBuildRankingStats()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head><title>Statistiques</title><meta charset="utf-8"></head>';
        echo '<body>';

        echo "<h1>Statistiques</h1>";

        $year = _root::getParam('year');
        if (null == $year) {
            echo "<p>Pas d'année !</p>";
        } else {
            $toAwards = model_award::getInstance()->findAllAwardsByYear($year);
            foreach ($toAwards as $oAward) {
                echo "<h2>", $oAward->toString(), "</h2>";
                $toAllStats = model_stats::getInstance()->calcAllStatistics($oAward);

                foreach ($toAllStats as $toStats) {
                    echo "<table>", "<thead><tr>";
                    if ($toStats->getType() == model_stats::PARTICIPATION) {
                        echo "<th>award_id</th>", "<th>award_title</th>", "<th>gender</th>", "<th>age</th>",
                        "<th>category</th>", "<th>count</th>", "<th>average_age</th>",
                        "<th>min_age</th>", "<th>max_age</th>";
                        echo "</tr></thead>", "<tbody>";
                        foreach ($toStats->getStats() as $oParticipation) {
                            echo "<tr>";
                            echo "<td>", $oParticipation->getAward()->getId(), "</td>";
                            echo "<td>", $oParticipation->getAward()->toString(), "</td>";
                            echo "<td>", $oParticipation->getGender(), "</td>";
                            echo "<td>", $oParticipation->getAge(), "</td>";
                            echo "<td>", $oParticipation->getCategory(), "</td>";
                            echo "<td>", $oParticipation->getCount(), "</td>";
                            echo "<td>", $oParticipation->getAverageAge(), "</td>";
                            echo "<td>", $oParticipation->getMinAge(), "</td>";
                            echo "<td>", $oParticipation->getMaxAge(), "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<th>award_id</th>", "<th>award_title</th>", "<th>gender</th>", "<th>age</th>",
                        "<th>title_id</th>", "<th>rang</th>", "<th>title</th>",
                        "<th>nb_votes</th>", "<th>average_votes</th>";
                        echo "</tr></thead>", "<tbody>";
                        foreach ($toStats->getStats() as $oRank) {
                            echo "<tr>";
                            echo "<td>", $oRank->getAward()->getId(), "</td>";
                            echo "<td>", $oRank->getAward()->toString(), "</td>";
                            echo "<td>", $oRank->getGender(), "</td>";
                            echo "<td>", $oRank->getAge(), "</td>";
                            echo "<td>", $oRank->getTitle()->getId(), "</td>";
                            echo "<td>", $oRank->getRank(), "</td>";
                            echo "<td>", $oRank->getTitle()->toString(), "</td>";
                            echo "<td>", $oRank->getNbVotes(), "</td>";
                            echo "<td>", $oRank->getAverage(), "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
            }

        }
        echo '</body></html>';
    }
}
