<?php

include dirname(__FILE__) . "/../../main/php/Suchgenie/ServerNameBuilder.php";

class ServerNames implements Suchgenie_ServerNameBuilder {
    
    private $servernames = array();
    
    function __constructor($nick) {
        $this->servernames[] = "http://" . $nick . "1.suchgenie.com";
        $this->servernames[] = "http://" . $nick . "2.suchgenie-backup.de";
    }
    
    function getServerNames() {
        return $this->servernames;
    }
}
