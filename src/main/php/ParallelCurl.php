<?php

class ParallelCurl {

    private $userAgent = '';
    private $mh;

    public function __construct($userAgent = '') {
        $this->userAgent = $userAgent;
        $this->mh = curl_multi_init();
    }

    public function close() {
        curl_multi_close($this->mh);
    }

    public function addGetRequest($url, $getParams = array()) {
        if($getParams !== array()) {
            $url .= "?" + http_build_query($getParams);
        }
        $ch = $this->getCurlHandle();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_multi_add_handle($this->mh, $ch);
    }

    public function addPostRequest($url, $postParams = array(), $getParams = array()) {
        if($getParams !== array()) {
            $url .= "?" + http_build_query($getParams);
        }
        $ch = $this->getCurlHandle();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_multi_add_handle($this->mh, $ch);
    }
    
    private function getCurlHandle ()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate");
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        return $ch;
    }

    public function getFirstResponse() {
        $active = 0;
        do {
            $status = curl_multi_exec($this->mh, $active);
            $info = curl_multi_info_read($this->mh);
            if (false !== $info && $info['result'] == CURLE_OK) {
                return curl_multi_getcontent($info['handle']);
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);
        return null;
    }

}
