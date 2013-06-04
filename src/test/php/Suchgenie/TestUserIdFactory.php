<?php

require_once dirname(__FILE__) . "/../../../main/php/Suchgenie/UserIdFactory.php";

class TestUserIdFactory implements Suchgenie_UserIdFactory {
    public function getUserId() {
        return substr(md5("test"), 0, 24);
    }
}
