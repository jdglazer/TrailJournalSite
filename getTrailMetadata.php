<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/target/config.php";

use Action\SaveJournalAction;
use DataAccess\DataAccessObjects\TrailQuery;

$trails;

if ( isset($_POST[SaveJournalAction::POST_TRAIL_ID]) ) {
    $trails = [TrailQuery::create()->findPk($_POST[SaveJournalAction::POST_TRAIL_ID])];
} else {
    $trails = TrailQuery::create()->find();
}

$jsonIn = array( "trails" => array() );

foreach ($trails as $trail) {
    array_push( $jsonIn["trails"], 
                array("id" => $trail->getId(), 
                      "name" => $trail->getName(),
                      "shortName" => $trail->getShortName(),
                      "length" => $trail->getLength(), 
                      "direction" => $trail->getDirections()
                    )  
              );
}

echo json_encode($jsonIn);


