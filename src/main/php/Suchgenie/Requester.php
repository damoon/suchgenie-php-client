<?php

require_once dirname(__FILE__) . '/ServerNameSource.php';

abstract class Suchgenie_Requester {

    protected $userId;
    protected $buildServernames;

    public function __construct($userId, Suchgenie_ServerNameSource $buildServernames) {
        $this->setUserId($userId);
        $this->buildServernames = $buildServernames;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    protected function getParallelGet($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMicrosec();

        $servers = $this->buildServernames->getServerNames();

        $download = new Tools_ParallelCurl();
        $download->addGetRequest($servers[0] . $path, $params);
        $download->addGetRequest($servers[1] . $path, $params);

        return $this->getJson($download);
    }

    protected function getParallelPost($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMicrosec();

        $servers = $this->buildServernames->getServerNames();

        $download = new Tools_ParallelCurl();
        $download->addPostRequest($servers[0] . $path, $params);
        $download->addPostRequest($servers[1] . $path, $params);

        return $this->getJson($download);
    }

    private function getTimestampMicrosec() {
        // this has more precission than microtime(true)
        list($usec, $sec) = explode(" ", microtime());
        return $sec . substr($usec, 2, 6); // 0.12345600 -> 123456

    }
    
    protected function getJson($download) {
        while (true) {
            $content = $download->getFirstResponse();
            if ($content == null) {
                return null;
            }
            $json = json_decode($content, true);
            if (is_array($json)) {
                return $json;
            }
        }
    }
}
