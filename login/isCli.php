<?php
    function isCli(){ // Check if the script is run on the command prompt
        if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
            return true;
        } else {
            return false;
        }
    }
