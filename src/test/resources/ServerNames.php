<?php

include dirname(__FILE__) . "/../../main/php/Suchgenie/ServerNameSource.php";

class ServerNames implements Suchgenie_ServerNameSource {
    
    private $nick;
    
    function __construct($nick) {
        $this->nick = $nick;
    }
    
    function getServerNames() {
        $domain1 = "suchgenie.com";
        $domain2 = "suchgenie-backup.de";
        if (rand(0, 1) == 1) {
            $domain1 = "suchgenie-backup.de";
            $domain2 = "suchgenie.com";
        }      
        return array(
            "http://" . $this->nick . "1." . $domain1,
            "http://" . $this->nick . "2." . $domain2
        );
    }
}
