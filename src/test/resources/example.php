<?php

require_once dirname(__FILE__) . "/ExampleClient.php";

$client = ExampleClient::getInstance();

$request = $client->initRequest()->setQuery("sonne");

$docIds = $request->getDocumentIdentifiers();

$navigation = $request->getNavigation(array("word"));

$logging = $client->logOrder(array("13"));

$docIdsAndNavigation = $request->getDocumentIdentifiersAndNavigation(array("word"));

var_dump($docIds);

var_dump($navigation);

var_dump($logging);

var_dump($docIdsAndNavigation);
