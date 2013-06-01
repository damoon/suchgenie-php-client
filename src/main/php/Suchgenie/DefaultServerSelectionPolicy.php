<?php

require_once dirname(__FILE__) . "/ServerSelectionPolicy.php";

class Suchgenie_DefaultServerSelectionPolicy implements Suchgenie_ServerSelectionPolicy {
    
    private static $VERISIGN_DOMAIN = "suchgenie.com";
    private static $DENIC_DOMAIN = "suchgenie-backup.de";
    
    public function getDomains($database){
        if (rand(0, 1) == 1) {
            $domain1 = self::$DENIC_DOMAIN;
            $domain2 = self::$VERISIGN_DOMAIN;
        }      
        else {
            $domain1 = self::$VERISIGN_DOMAIN;
            $domain2 = self::$DENIC_DOMAIN;
        }
        return array(
            "http://" . $database . "1." . $domain1,
            "http://" . $database . "2." . $domain2
        );
    }
}
