<?php

include dirname(__FILE__) . "/../../main/php/Suchgenie/ServerNameSource.php";

class ServerNames implements Suchgenie_ServerNameSource {
    
    private $servernames = array();
    
    function __constructor($nick) {
        $this->servernames[] = "http://" . $nick . "1.suchgenie.com";
        $this->servernames[] = "http://" . $nick . "2.suchgenie-backup.de";
    }
    
    function getServerNames() {
        return $this->servernames;
    }
}
