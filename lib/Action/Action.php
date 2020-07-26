<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Action;

/**
 * Description of Action
 *
 * @author jglazer
 */
abstract class Action {
    
    protected $sessionErrorKeyName;
    protected $acceptedPostVariables;
    protected $invalidPostVariables = [];
            
    public abstract function execute(&$postDictionary);
    
    protected function addPostVariable($postVarName, $required = true) {
        $this->acceptedPostVariables[$postVarName] = $required;
    }
    
    protected function validatePostVariables($postDictionary) {
        $this->invalidPostVariables = [];
        
        foreach ($this->acceptedPostVariables as $postVarName=>$isRequired) {
            
            if ($isRequired && !array_key_exists($postVarName, $postDictionary)) {
                $this->invalidPostVariables[$postVarName] = "missing";
                continue;
            } 
            
            $validateMethodName = "validate".ucfirst($postVarName);
            
            if (method_exists($this, $validateMethodName)
                    && array_key_exists($postVarName, $postDictionary)) {
                
                $validationReturn = $this->$validateMethodName($postDictionary[$postVarName]);
                
                if (!is_null($validationReturn) && !empty($validationReturn)) {
                    $this->invalidPostVariables[$postVarName] = $validationReturn;
                }
            }
        }
        
        return empty($this->invalidPostVariables);
    }
    
    protected function onFailure($errorMsg) {
        $_SESSION[$this->sessionErrorKeyName] = $errorMsg;
    }
    
    public function setSessionErrorKeyName($sessionErrorKeyName) {
        $this->sessionErrorKeyName = $sessionErrorKeyName;
    }
}