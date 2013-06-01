<?php

class Suchgenie_ConnectionFactory {
    
    private $database;
    private $connectTimeout;
    private $readTimeout;
    private $username;
    private $password;
    private $serverSelectionPolicy;
    private $userIdFactory;
    
    public function __construct($database,
            $connectTimeout, $readTimeout,
            $username, $password,
            Suchgenie_ServerSelectionPolicy $serverSelectionPolicy,
            Suchgenie_UserIdFactory $userIdFactory) {
        $this->database = $database;
        $this->connectTimeout = $connectTimeout;
        $this->readTimeout = $readTimeout;
        $this->username = $username;
        $this->password = $password;
        $this->serverSelectionPolicy = $serverSelectionPolicy;
        $this->userIdFactory = $userIdFactory;
    }

    private function getTimestampMicrosec() {
        // this has more precission than microtime(true)
        list($usec, $sec) = explode(" ", microtime());
        return $sec . substr($usec, 2, 6); // 0.12345600 -> 123456
    }

    private function createNewDownload() {
        $download = new Tools_ParallelCurl();
        if (isset($this->username)) {
            $download->setAuth($this->username, $this->password);
        }
        $download->setConnectionTimeout($this->connectTimeout);
        $download->setReadTimeout($this->readTimeout);
        return $download;
    }
    
    public function getParallelGet($path, $params) {
        $servers = $this->serverSelectionPolicy->getDomains($this->database);
        $download = $this->createNewDownload();
        $params['userId'] = $this->userIdFactory->getUserId();
        $params['ts'] = $this->getTimestampMicrosec();
        $download->addGetRequest($servers[0] . $path, $params);
        $download->addGetRequest($servers[1] . $path, $params);
        return $download;
    }

    public function getParallelPost($path, $params) {
        $servers = $this->serverSelectionPolicy->getDomains($this->database);
        $download = $this->createNewDownload();
        $params['userId'] = $this->userIdFactory->getUserId();
        $params['ts'] = $this->getTimestampMicrosec();
        $download->addPostRequest($servers[0] . $path, $params);
        $download->addPostRequest($servers[1] . $path, $params);
        return $download;
    }
}
