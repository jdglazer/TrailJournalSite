<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Action;

use DataAccess\DataAccessObjects\Journalday;
use DataAccess\DataAccessObjects\JournaldayQuery;
/**
 * Description of SaveJournalAction
 *
 * @author jglazer
 */
class SaveJournalAction extends Action {
    
    const POST_ID           = "id";
    const POST_IMAGE_OF_DAY = "imageOfDayId";
    const POST_DIRECTION    = "direction";
    const POST_TRAIL_ID     = "trailId";
    const POST_DATE         = "date";
    const POST_START_POINT  = "startPoint";
    const POST_END_POINT    = "endPoint";
    const POST_JOURNAL      = "journal";
    
    function __construct() {
        $this->addPostVariable(self::POST_ID, false);
        $this->addPostVariable(self::POST_IMAGE_OF_DAY, false);
        $this->addPostVariable(self::POST_DIRECTION);
        $this->addPostVariable(self::POST_TRAIL_ID);
        $this->addPostVariable(self::POST_DATE);
        $this->addPostVariable(self::POST_START_POINT);
        $this->addPostVariable(self::POST_END_POINT);
        $this->addPostVariable(self::POST_JOURNAL);
    }
    
    public function execute(&$postDictionary) {
        if (!parent::validatePostVariables($postDictionary)) {
            // TO DO: fix this error parsing logic
            $errorMsg = "";
            foreach( $this->invalidPostVariables as $var=>$error) {
                $errorMsg .= "Failed on post variable ".$var." with error: ".$error."<br/>";
            }
            $this->onFailure($errorMsg);
            return;
        }
        
        $journalEntry = null;
        
        // Edit mode
        if (array_key_exists(self::POST_ID, $postDictionary) && !empty($postDictionary[self::POST_ID])) {
            $id = $postDictionary[self::POST_ID];
            try {
                $journalEntry = JournaldayQuery::create()->findPk($id);
            } catch (Exception $ex) {
                $this->onFailure("Internal database error");
                return;
            }
            
            if (is_null($journalEntry)) {
                $this->onFailure("Journal entry not found. It is possible it has been removed.");
                return;
            }
            // call function to update entry
        } 
        // Create new mode
        else {
            $journalEntry = new Journalday();
        }
        
        $journalEntry->setDate($postDictionary[self::POST_DATE]);
        $journalEntry->setTrailId($postDictionary[self::POST_TRAIL_ID]);
        $journalEntry->setStartPoint($postDictionary[self::POST_START_POINT]);
        $journalEntry->setEndPoint($postDictionary[self::POST_END_POINT]);
        $journalEntry->setJournal($postDictionary[self::POST_JOURNAL]);
        $journalEntry->setImageOfDayId($postDictionary[self::POST_IMAGE_OF_DAY]);
        $journalEntry->setDirection($postDictionary[self::POST_DIRECTION]);
        
        try {
            $affectedRows = $journalEntry->save();

            if ($affectedRows == 0) {
                $this->onFailure("Internal error attempting to save journal");
            } else {
                $postDictionary[self::POST_ID] = $journalEntry->getId();
            }
        } catch(Exception $ex) {
            $this->onFailure("Failed to save journal due to internal Exception");
        }
    }
}
