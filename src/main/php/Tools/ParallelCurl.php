<?php

class Tools_ParallelCurl {

    private $userAgent;
    private $timeout = 10;
    private $mh;

    private $username;
    private $password;

    public function __construct($userAgent = 'php/parallelCurl') {
        $this->userAgent = $userAgent;
    }
    
    public function isClosed() {
        return $this->mh == null;
    }

    public function close() {
        curl_multi_close($this->mh);
        $this->mh = null;
    }
    
    public function setTimeout ($timeout) {
        $this->timeout = $timeout;
    }

    public function setAuth ($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function addGetRequest($url, $getParams = array()) {
        if ($this->isClosed()) {
            $this->mh = curl_multi_init();
        }
        
        if($getParams !== array()) {
            $url .= "?" . http_build_query($getParams);
        }
        $ch = $this->getCurlHandle($url);
        curl_multi_add_handle($this->mh, $ch);
    }

    public function addPostRequest($url, $postParams = array(), $getParams = array()) {
        if ($this->isClosed()) {
            $this->mh = curl_multi_init();
        }
        
        if($getParams !== array()) {
            $url .= "?" . http_build_query($getParams);
        }
        $ch = $this->getCurlHandle($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postParams));
        curl_multi_add_handle($this->mh, $ch);
    }

    public function getFirstResponse() {
        if ($this->isClosed()) {
            return null;
        }

        $active = 0;
        do {
            $status = curl_multi_exec($this->mh, $active);
            $info = curl_multi_info_read($this->mh);
            if (false !== $info && $info['result'] == CURLE_OK) {
                return curl_multi_getcontent($info['handle']);
            }
            usleep(2000);
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);
        return null;
    }

    private function getCurlHandle ($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate");
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (isset($this->username)) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }
        return $ch;
    }

}

