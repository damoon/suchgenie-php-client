<?php

class Suchgenie_JsonDownload {
    
    private $download;
    
    public function __constructor (Tools_ParallelCurl $download) {
        $this->download = $download;
    }
    
    public function getJson() {
        while (true) {
            $content = $this->download->getFirstResponse();
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
