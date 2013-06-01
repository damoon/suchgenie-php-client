<?php

require_once dirname(__FILE__) . "/ExampleClient.php";

$client = ExampleClient::getInstance();

$request = $client->initRequest()->setQuery("sonne");

$docIds = $request->getDocumentIdentifiers();

$navigation = $request->getNavigation(array("word"));

var_dump($docIds);

var_dump($navigation);
