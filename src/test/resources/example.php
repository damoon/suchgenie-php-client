<?php

include dirname(__FILE__) . "/ExampleClient.php";

$client = ExampleClient::getInstance();

$request = $client->initRequest()
    ->setQuery("searchTerm")
    ->setDocumentsPerPage(24)
    ->setEqualsFilter("shape", "round");

$docIds = $request->getDocumentIdentifiers();
$navigation = $request->getNavigation(array("name", "mass"));

$client->logSearch("searchTerm");
