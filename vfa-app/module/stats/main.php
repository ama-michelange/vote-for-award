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
        $year = _root::getParam('year');
        $html = $this->buildRankingStats($year);
        $oView = new _view('stats::list');
        $oView->title = 'Statistiques et classements ' . $year;
        $oView->showHtml = $html;
        $this->oLayout->add('work', $oView);
    }


    /**
     * @return string
     */
    private function buildRankingStats($year = null)
    {
        $ret = "";
        if (null == $year) {
            $ret .= "<p>Pas d'année !</p>";
        } else {
            $order = 0;
            $toAwards = model_award::getInstance()->findAllAwardsByYear($year);
            foreach ($toAwards as $oAward) {
                $ret .= "<h1>" . $oAward->toString() . "</h1>";

                $toAllStats = model_stats::getInstance()->calcAllStatistics($oAward);
                foreach ($toAllStats as $toStats) {
                    if ($toStats->getType() == model_stats::PARTICIPATION) {
                        $ret .= '<table class="table table-striped table-hover">' . "<thead><tr>";
                        $ret .= "<th>order</th>" . "<th>type</th>" . "<th>award_id</th>" . "<th>award_title</th>" .
                            "<th>group_id</th>" . "<th>group_name</th>" .
                            "<th>gender</th>" . "<th>age</th>" . "<th>category</th>" .
                            "<th>users_count</th>" . "<th>average_age</th>" . "<th>min_age</th>" . "<th>max_age</th>" .
                            "<th>groups_count</th>";
                        $ret .= "</tr></thead>" . "<tbody>";
                        foreach ($toStats->getStats() as $oParticipation) {
                            $order++;
//                            echo "<br> MAIN.Part A ",$order," - ", $oParticipation->getAward()->toString(), " - ", $oParticipation->getGroupName();
                            $ret .= "<tr>";
                            $ret .= "<td>" . $order . "</td>";
                            $ret .= "<td>" . $oParticipation->getType() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAward()->getId() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAward()->toString() . "</td>";
                            $ret .= "<td>" . $oParticipation->getGroupId() . "</td>";
                            $ret .= "<td>" . $oParticipation->getGroupName() . "</td>";
                            $ret .= "<td>" . $oParticipation->getGender() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getCategory() . "</td>";
                            $ret .= "<td>" . $oParticipation->getUsersCount() . "</td>";
                            $ret .= "<td>" . $oParticipation->getAverageAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getMinAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getMaxAge() . "</td>";
                            $ret .= "<td>" . $oParticipation->getGroupsCount() . "</td>";
                            $ret .= "</tr>";
//                            echo "<br> MAIN.Part B ",$order," - ", $oParticipation->getAward()->toString(), " - ", $oParticipation->getGroupName();
                        }
                        $ret .= "</tbody>";
                        $ret .= "</table>";
                    } else {
                        $ret .= '<table class="table table-striped table-hover">' . "<thead><tr>";
                        $ret .= "<th>order</th>" . "<th>type</th>" . "<th>award_id</th>" . "<th>award_title</th>" .
                            "<th>group_id</th>" . "<th>group_name</th>" .
                            "<th>gender</th>" . "<th>age</th>" .
                            "<th>rang</th>" . "<th>title_id</th>" . "<th>title</th>" .
                            "<th>nb_votes</th>" . "<th>average_votes</th>";
                        $ret .= "</tr></thead>" . "<tbody>";
                        foreach ($toStats->getStats() as $oRank) {
                            $order++;
                            $ret .= "<tr>";
                            $ret .= "<td>" . $order . "</td>";
                            $ret .= "<td>" . $oRank->getType() . "</td>";
                            $ret .= "<td>" . $oRank->getAward()->getId() . "</td>";
                            $ret .= "<td>" . $oRank->getAward()->toString() . "</td>";
                            $ret .= "<td>" . $oRank->getGroupId() . "</td>";
                            $ret .= "<td>" . $oRank->getGroupName() . "</td>";
                            $ret .= "<td>" . $oRank->getGender() . "</td>";
                            $ret .= "<td>" . $oRank->getAge() . "</td>";
                            $ret .= "<td>" . $oRank->getRank() . "</td>";
                            $ret .= "<td>" . $oRank->getTitle()->getId() . "</td>";
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
}
