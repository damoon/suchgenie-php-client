<?php

require_once dirname(__FILE__) . '/../Tools/ParallelCurl.php';
require_once dirname(__FILE__) . '/Requester.php';
require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/UserIdFactory.php';
require_once dirname(__FILE__) . '/ServerSelectionPolicy.php';

class Suchgenie_Client extends Suchgenie_Requester {

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

    public function logEvent($event, $params = array()) {
        $params['event'] = $event;
        return $this->getParallelPost("/api/log.json", $params);
    }

}
