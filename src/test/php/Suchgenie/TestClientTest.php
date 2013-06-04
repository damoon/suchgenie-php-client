<?php

require_once dirname(__FILE__) . "/TestClient.php";

class TestClientTest extends PHPUnit_Framework_TestCase {
    
    public function testLogging() {
        $client = TestClient::getInstance();
        $response = $client->logSearch("some query");
        $this->assertEquals("true", $response['successful']);
    }

    public function testAutocompletions() {
        $client = TestClient::getInstance();
        $response = $client->getAutocompletions("s");
        $this->assertEquals(array(
                'value' => 'SuchGenie',
                'label' => '<strong>S</strong>uchGenie'), $response[0]);
        $this->assertEquals("Sonnencreme", $response[1]['value']);
        $this->assertEquals(12, count($response));
    }

    public function testAutocompletionsSmaller() {
        $client = TestClient::getInstance();
        $response = $client->getAutocompletions("s", 3);
        $this->assertEquals(array(
                'value' => 'SuchGenie',
                'label' => '<strong>S</strong>uchGenie'), $response[0]);
        $this->assertEquals("Sonnencreme", $response[1]['value']);
        $this->assertEquals(3, count($response));
    }
    
    public function testFirstPageDocuments() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setDocumentsPerPage(1)->setPageNumber(1);
        $response = $request->getDocuments(array("word"));
        $this->assertEquals(array(array("word"=>"Sonnencreme")), $response['documents']);
    }
    
    public function test2ndPageDocuments() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setDocumentsPerPage(1)->setPageNumber(2);
        $response = $request->getDocuments(array("word"));
        $this->assertEquals(array(array("word"=>"Sonnensystem")), $response['documents']);
    }
    
    public function testDocuments() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne");
        $response = $request->getDocuments(array("word"));
        $this->assertEquals(array(array("word"=>"Sonnencreme"), array("word"=>"Sonnensystem")), $response['documents']);
    }
    
    public function testDocumentIdentifers() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne");
        $response = $request->getDocumentIdentifiers();
        $this->assertEquals(array(1, 13), $response['documentIdentifiers']);
    }
    
    public function testNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne");
        $response = $request->getNavigation(array("word"));
        $this->assertEquals(array("Sonnencreme"=>1, "Sonnensystem"=>1), $response['word']);
    }
    
    public function testDocumentIdentifersAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne");
        $response = $request->getDocumentIdentifiersAndNavigation(array("word"));
        $this->assertEquals(array(1, 13), $response['page']['documentIdentifiers']);
        $this->assertEquals(array("Sonnencreme"=>1, "Sonnensystem"=>1), $response['navigation']['word']);
    }
    
    public function testDocumentsAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne");
        $response = $request->getDocumentsAndNavigation(array("word"), array("word"));
        $this->assertEquals(array(array("word"=>"Sonnencreme"), array("word"=>"Sonnensystem")), $response['page']['documents']);
        $this->assertEquals(array("Sonnencreme"=>1, "Sonnensystem"=>1), $response['navigation']['word']);
    }

    public function testFilteredDocuments() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setGreaterEqualFilter("results", "1500000");
        $response = $request->getDocuments(array("word"));
        $this->assertEquals(array(array("word"=>"Sonnensystem")), $response['documents']);
    }
    
    public function testFilteredDocumentIdentifers() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setGreaterEqualFilter("results", "1500000");
        $response = $request->getDocumentIdentifiers();
        $this->assertEquals(array(13), $response['documentIdentifiers']);
    }
    
    public function testFilteredNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setGreaterEqualFilter("results", "1500000");
        $response = $request->getNavigation(array("word"));
        $this->assertEquals(array("Sonnensystem"=>1), $response['word']);
    }
    
    public function testFilteredDocumentIdentifersAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setLessThenFilter("results", "1500000");
        $response = $request->getDocumentIdentifiersAndNavigation(array("word"));
        $this->assertEquals(array(1), $response['page']['documentIdentifiers']);
        $this->assertEquals(array("Sonnencreme"=>1), $response['navigation']['word']);
    }
    
    public function testFilteredDocumentsAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setLessThenFilter("results", "1500000");
        $response = $request->getDocumentsAndNavigation(array("word"), array("word"));
        $this->assertEquals(array(array("word"=>"Sonnencreme")), $response['page']['documents']);
        $this->assertEquals(array("Sonnencreme"=>1), $response['navigation']['word']);
    }
    
    public function testSortedDocuments() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setSorting("results", "desc");
        $response = $request->getDocuments(array("word", "results"));
        $this->assertEquals(array(array("word"=>"Sonnensystem", "results"=>"1660000"), array("word"=>"Sonnencreme", "results"=>"1340000")), $response['documents']);
    }
    
    public function testSortedDocumentIdentifers() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setSorting("results", "desc");
        $response = $request->getDocumentIdentifiers();
        $this->assertEquals(array(13, 1), $response['documentIdentifiers']);
    }
    
    public function testSortedNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setSorting("results", "desc");
        $response = $request->getNavigation(array("word"));
        $this->assertEquals(array("Sonnensystem"=>1, "Sonnencreme"=>1), $response['word']);
    }
    
    public function testSortedDocumentIdentifersAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setNavigationSorting("results", "desc")->setDocumentsSorting("results", "desc");
        $response = $request->getDocumentIdentifiersAndNavigation(array("word"));
        $this->assertEquals(array("Sonnensystem"=>1, "Sonnencreme"=>1), $response['navigation']['word']);
        $this->assertEquals(array(13, 1), $response['page']['documentIdentifiers']);
    }
    
    public function testSortedDocumentsAndNavigation() {
        $client = TestClient::getInstance();
        $request = $client->initRequest();
        $request->setQuery("sonne")->setNavigationSorting("results", "desc")->setDocumentsSorting("results", "desc");
        $response = $request->getDocumentsAndNavigation(array("word", "results"), array("word"));
        $this->assertEquals(array(array("word"=>"Sonnensystem", "results"=>1660000), array("word"=>"Sonnencreme", "results"=>1340000)), $response['page']['documents']);
        $this->assertEquals(array("Sonnensystem"=>1, "Sonnencreme"=>1), $response['navigation']['word']);
    }
}
