<?php

echo "AGENT: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
echo "GET: " . json_encode($_GET) . "\n";
echo "POST: " . json_encode($_POST) . "\n";

if (isset($_REQUEST['delay'])) {
        $delay = max(0, min((int)$_REQUEST['delay'], 15));
        sleep($delay);
        echo "slept for $delay sec\n";
}

echo "done";
