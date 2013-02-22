<?php

require_once dirname(__FILE__) . '/../Tools/ParallelCurl.php';
require_once dirname(__FILE__) . '/Requester.php';
require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/ServerNameSource.php';

abstract class Suchgenie_Client extends Suchgenie_Requester {

    public function __construct(Suchgenie_ServerNameSource $buildServernames) {
        parent::__construct($this->generatedUserId(), $buildServernames);
    }

    public function generatedUserId() {
        $ip = isset($_SERVER) && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $userAgent = isset($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        return substr(md5($ip . $userAgent), 0, 24);
    }
    
    public function initRequest() {
        return new Suchgenie_Request($this->userId, $this->buildServernames);
    }

    public function triggerImport() {
        $servers = $this->buildServernames->getServers();
        $download = new Tools_ParallelCurl();
        $download->addPostRequest($servers[0] . "/api/import.json", array());
        return $this->getJson($download) != null;
    }

    public function getAutocompletions($query, $numberOfAutocompletions) {
        $params = array(
            'query' => $query,
            'numberOfAutocompletions' => $numberOfAutocompletions
        );
        return $this->getParallelGet("/api/autocompletions.json", $params);
    }

    public function logSearch($query) {
        $params = array(
            'event' => 'search',
            'query' => $query
        );
        return $this->getParallelPost("/api/log.json", $params);
    }

    public function logSearchExtended($query) {
        $params = array(
            'event' => 'searchExtended',
            'query' => $query
        );
        return $this->getParallelPost("/api/log.json", $params);
    }

    public function logDocumentView($documentIdentifier) {
        $params = array(
            'event' => 'documentView',
            'documentIdentifier' => $documentIdentifier
        );
        return $this->getParallelPost("/api/log.json", $params);
    }

    public function logPreparedOrder($documentIdentifier) {
        $params = array(
            'event' => 'preparedOrder',
            'documentIdentifier' => $documentIdentifier
        );
        return $this->getParallelPost("/api/log.json", $params);
    }

    public function logOrder(array $documentIdentifiers) {
        $params = array(
            'event' => 'order',
            'documentIdentifiers' => implode(',', $documentIdentifiers)
        );
        return $this->getParallelPost("/api/log.json", $params);
    }

}
