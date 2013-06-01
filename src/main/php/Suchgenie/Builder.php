<?php

require_once dirname(__FILE__) . "/DefaultServerSelectionPolicy.php";
require_once dirname(__FILE__) . "/UserIdFactory.php";
require_once dirname(__FILE__) . "/Client.php";
require_once dirname(__FILE__) . "/ConnectionFactory.php";

class Suchgenie_Builder {

    private $database;
    private $connectTimeout;
    private $readTimeout;
    private $username;
    private $password;
    private $serverSelectionPolicy;
    private $userIdFactory;
    
    public function __construct() {
        $this->serverSelectionPolicy = new Suchgenie_DefaultServerSelectionPolicy();
    }
    
    public function withDatabase($database) {
	$this->database = $database;
	return $this;
    }

    public function withConnectTimeout($connectTimeout) {
	$this->connectTimeout = $connectTimeout;
	return $this;
    }

    public function withReadTimeout($readTimeout) {
	$this->readTimeout = $readTimeout;
	return $this;
    }

    public function withAuthentication($username, $password) {
	$this->username = $username;
	$this->password = $password;
	return $this;
    }

    public function withServerSelectionPolicy(Suchgenie_ServerSelectionPolicy $serverSelectionPolicy) {
        $this->serverSelectionPolicy = $serverSelectionPolicy;
	return $this;
    }

    public function withUserIdFactory(Suchgenie_UserIdFactory $userIdFactory) {
	$this->userIdFactory = $userIdFactory;
	return $this;
    }
    
    public function build () {
        if (is_null($this->userIdFactory)) {
            throw new RuntimeException("userIdFactory is missing");
        }
        $connectionFactory = new Suchgenie_ConnectionFactory(
                $this->database,
                $this->connectTimeout, $this->readTimeout,
                $this->username, $this->password,
                $this->serverSelectionPolicy,
                $this->userIdFactory
                );
        return new Suchgenie_Client($connectionFactory);
    }
}
