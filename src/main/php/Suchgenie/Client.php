<?php

require_once dirname(__FILE__) . '/../Tools/ParallelCurl.php';
require_once dirname(__FILE__) . '/JsonDownload.php';

class Suchgenie_Client {

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
        return substr(md5($ip . $userAgent), 0, 24);
    }

    public function triggerImport() {
        $server1 = "http://" . $this->databaseName . "1.suchgenie.com";
        $server2 = "http://" . $this->databaseName . "1.suchgenie-backup.de";
        $download = new ParallelCurl();
        $download->addPostRequest($server1 . "/importer", array());
        $download->addPostRequest($server2 . "/importer", array());
        return $download;
    }

    public function getAutocompletions($query, $numberOfAutocompletions) {
        $params = array();
        $params['query'] = $query;
        $params['numberOfAutocompletions'] = $numberOfAutocompletions;

        $download = $this->getParallelGet("/api/autocompletions.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function getDocumentIdentifiers($query, $pageNumber, $documentsPerPage) {
        $params = array();
        $params['query'] = $query;
        $params['pageNumber'] = $pageNumber;
        $params['documentsPerPage'] = $documentsPerPage;

        $download = $this->getParallelGet("/api/documentIdentifiers.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function logSearch($query) {
        $params = array('event' => 'search', 'query' => $query);
        $download = $this->getParallelPost("/api/log.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function logSearchExtended($query) {
        $params = array('event' => 'searchExtended', 'query' => $query);
        $download = $this->getParallelPost("/api/log.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function logDocumentView($documentIdentifier) {
        $params = array('event' => 'documentView', 'documentIdentifier' => $documentIdentifier);
        $download = $this->getParallelPost("/api/log.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function logPreparedOrder($documentIdentifier) {
        $params = array('event' => 'preparedOrder', 'documentIdentifier' => $documentIdentifier);
        $download = $this->getParallelPost("/api/log.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    public function logOrder(array $documentIdentifiers) {
        $params = array('event' => 'order', 'documentIdentifiers' => implode(',', $documentIdentifiers));
        $download = $this->getParallelPost("/api/log.json", $params);
        $jsonDownload = new Suchgenie_JsonDownload($download);
        return $jsonDownload->getJson();
    }

    private function getTimestampMicrosec() {
        // this has more precission than microtime(true)
        list($usec, $sec) = explode(" ", microtime());
        return $sec . substr($usec, 2, 6); // 0.12345600 -> 123456

    }

    private function getParallelGet($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMicrosec();

        $server1 = "http://" . $this->databaseName . "1.suchgenie.com";
        $server2 = "http://" . $this->databaseName . "2.suchgenie-backup.de";

        $download = new Tools_ParallelCurl();
        $download->addGetRequest($server1 . $path, $params);
        $download->addGetRequest($server2 . $path, $params);

        return $download;
    }

    private function getParallelPost($path, $params) {
        $params['userId'] = $this->userId;
        $params['ts'] = $this->getTimestampMicrosec();

        $server1 = "http://" . $this->databaseName . "1.suchgenie.com";
        $server2 = "http://" . $this->databaseName . "2.suchgenie-backup.de";

        $download = new Tools_ParallelCurl();
        $download->addPostRequest($server1 . $path, $params);
        $download->addPostRequest($server2 . $path, $params);

        return $download;
    }

}
