<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/target/config.php";
require_once __DIR__ . "/siteConstants.php";
        
use Action\SaveJournalAction;

$saveJournalAction = new SaveJournalAction();
$saveJournalAction->setSessionErrorKeyName(POST_ERROR_MESSAGE_SESSION_KEY_NAME);
$saveJournalAction->execute($_POST);

// Set session variables with post variables, should probably be done in action execute
$_SESSION[POST_VARIABLES_SESSION_KEY_NAME] = $_POST;

header("Location: ".$_POST[REDIRECT_URL_POST_KEY_NAME]);
