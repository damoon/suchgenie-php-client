<?php

require_once __DIR__ . '/ParallelDownloader.php';

class SuchgenieClient {

    private $databaseName;
    private $userId;

    public function __construct($databaseName) {
        $this->databaseName = $databaseName;
        $this->setUserId($this->generatedUserId());
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function generatedUserId() {
        $ip = isset($_SERVER) && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $userAgent = isset($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        return substr(md5($ip, $userAgent), 0, 24);
    }

    public function triggerImport() {
        $server1 = "http://" + $this->databaseName + "1.suchgenie.com";
        $server2 = "http://" + $this->databaseName + "1.suchgenie-backup.de";        
        $download = new ParallelCurl();
        $download->addPostRequest($server1 + "/importer", array());
        $download->addPostRequest($server2 + "/importer", array());        
        return $download;
    }

    public function getAutocompletions($query) {
        $params = array();
        $params['query'] = $query;

        $download = $this->getParallelGet("/api/autocompletions", $params);
        return $this->getJsonDownload($download);
    }

    public function getDocumentIdentifiers($query) {
        $params = array();
        $params['query'] = $query;

        $download = $this->getParallelGet("/api/documentIdentifiers", $params);
        return $this->getJsonDownload($download);
    }

    public function logSearch($query) {
        $params = array('query' => $query);
        $download = $this->getParallelPost("/api/log/search", $params);
        return $this->getJsonDownload($download);
    }

    public function logSearchExtended($query) {
        $params = array('query' => $query);
        $download = $this->getParallelPost("/api/log/searchExtended", $params);
        return $this->getJsonDownload($download);
    }

    public function logDocumentView($documentIdentifier) {
        $params = array('documentIdentifier' => $documentIdentifier);
        $download = $this->getParallelPost("/api/log/documentView", $params);
        return $this->getJsonDownload($download);
    }

    public function logPreparedOrder($documentIdentifier) {
        $params = array('documentIdentifier' => $documentIdentifier);
        $download = $this->getParallelPost("/api/log/preparedOrder", $params);
        return $this->getJsonDownload($download);
    }

    public function logOrder(array $documentIdentifiers) {
        $params = array('documentIdentifiers' => implode(',', $documentIdentifiers));
        $download = $this->getParallelPost("/api/log/order", $params);
        return $this->getJsonDownload($download);
    }

    private function getTimestampMillisec() {
        // this has more precission than microtime(true)
        list($usec, $sec) = explode(" ", microtime());
        return $sec + (string)round($usec * 1000);
    }

    private function getParallelGet($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMillisec();
        
        $server1 = "http://" + $this->databaseName + "1.suchgenie.com";
        $server2 = "http://" + $this->databaseName + "2.suchgenie-backup.de";
        
        $download = new ParallelCurl();
        $download->addGetRequest($server1 + $path, $params);
        $download->addGetRequest($server2 + $path, $params);
        
        return $download;
    }

    private function getParallelPost($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMillisec();
        
        $server1 = "http://" + $this->databaseName + "1.suchgenie.com";
        $server2 = "http://" + $this->databaseName + "2.suchgenie-backup.de";
        
        $download = new ParallelCurl();
        $download->addPostRequest($server1 + $path, $params);
        $download->addPostRequest($server2 + $path, $params);
        
        return $download;
    }

    private function getJsonDownload($download) {
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
