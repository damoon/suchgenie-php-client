<?php

require_once dirname(__FILE__) . "/../../main/php/Suchgenie/UserIdFactory.php";

class ExampleUserIdFactory implements Suchgenie_UserIdFactory {
    public function getUserId() {
        $ip = isset($_SERVER) && isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $userAgent = isset($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        return substr(md5($ip . $userAgent), 0, 24);
    }
}
