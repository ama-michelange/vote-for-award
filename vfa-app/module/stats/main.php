<?php

class module_stats extends abstract_module
{
    public function _index()
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
                $toAllRanking = model_stats::getInstance()->calcAllRanking($oAward);
                foreach ($toAllRanking as $toRanking) {
                    echo "<table>", "<thead><tr>";
                    echo "<th>award_id</th>", "<th>award_title</th>", "<th>gender</th>", "<th>age</th>",
                         "<th>title_id</th>", "<th>rang</th>", "<th>title</th>",
                         "<th>nb_votes</th>", "<th>average_votes</th>";
                    echo "</tr></thead>", "<tbody>";
                    foreach ($toRanking as $oRank) {
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
                    echo "</tbody>";
                    echo "</table>";
                }
            }

        }
        echo '</body></html>';
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
}
