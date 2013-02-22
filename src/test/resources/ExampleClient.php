<?php

include dirname(__FILE__) . "/../../main/php/Suchgenie/Client.php";
include dirname(__FILE__) . "/ServerNames.php";

class ExampleClient extends Suchgenie_Client {

    static private $instance = null;
    
    static public function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self (new ServerNames());
        }
        return self::$instance;
    }

    public function __construct(Suchgenie_ServerNameSource $serverNames){
        if (self::$instance != null) {
            throw new RuntimeException("only one instance is allowed");
        }
        parent::__construct($serverNames);
    }
    private function __clone(){}
    
}
