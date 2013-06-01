<?php

require_once dirname(__FILE__) . "/../../main/php/Suchgenie/Builder.php";
require_once dirname(__FILE__) . "/ExampleUserIdFactory.php";

class ExampleClient {

    static private $instance = null;
    
    static public function getInstance() {
        if (null === self::$instance) {
            $builder = new Suchgenie_Builder();
            self::$instance = $builder
                    ->withDatabase("test")
                    ->withAuthentication("test", "test")
                    ->withUserIdFactory(new ExampleUserIdFactory())
                    ->build();
        }
        return self::$instance;
    }    
}
