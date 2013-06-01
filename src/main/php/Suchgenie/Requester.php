<?php

abstract class Suchgenie_Requester {

    protected $connectionFactory;

    public function __construct(Suchgenie_ConnectionFactory $connectionFactory) {
        $this->connectionFactory = $connectionFactory;
    }

    public function getParallelGet($path, $params) {
        return $this->getJson($this->connectionFactory->getParallelGet($path, $params));
    }

    public function getParallelPost($path, $params) {
        return $this->getJson($this->connectionFactory->getParallelPost($path, $params));
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
