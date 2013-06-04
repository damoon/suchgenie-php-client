<?php

require_once dirname(__FILE__) . '/../Tools/ParallelCurl.php';
require_once dirname(__FILE__) . '/Requester.php';
require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/UserIdFactory.php';
require_once dirname(__FILE__) . '/ServerSelectionPolicy.php';

class Suchgenie_Client extends Suchgenie_Requester {

    const VERSION = 'v2.5';
    
    public function initRequest() {
        return new Suchgenie_Request($this->connectionFactory);
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
            'query' => $query
        );
        return $this->logEvent("search", $params);
    }

    public function logSearchExtended($query) {
        $params = array(
            'query' => $query
        );
        return $this->logEvent("searchExtended", $params);
    }

    public function logDocumentView($documentIdentifier) {
        $params = array(
            'documentIdentifier' => $documentIdentifier
        );
        return $this->logEvent("documentView", $params);
    }

    public function logPreparedOrder($documentIdentifier) {
        $params = array(
            'documentIdentifier' => $documentIdentifier
        );
        return $this->logEvent("preparedOrder", $params);
    }

    public function logOrder(array $documentIdentifiers) {
        $params = array(
            'documentIdentifiers' => implode(',', $documentIdentifiers)
        );
        return $this->logEvent("order", $params);
    }

    public function logEvent($event, $params = array()) {
        $params['event'] = $event;
        return $this->getParallelPost("/api/log.json", $params);
    }

}
