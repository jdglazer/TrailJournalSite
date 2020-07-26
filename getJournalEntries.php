<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/target/config.php";

use DataAccess\DataAccessObjects\JournaldayQuery;

$journalDays = JournaldayQuery::create()->find();

$journals = array( "journals" => array() );


foreach ($journalDays as $day) {
    array_push($journals,
               array("id" => $day->getId(),
                     "date" => $day->getDate(),
                     "trail" => $day->getTrailId(),
                     "direction" => $day->getDirection(),
                     "startPoint" => $day->getStartPoint(),
                     "endPoint" => $day->getEndPoint(),
                     "journal" => $day->getJournal()
                )
          );
}

echo json_encode($journals);

?>