<?php

include "../../main/php/ParallelCurl.php";

class ParallelCurlTest extends PHPUnit_Framework_TestCase {

    public function testOpensAndCloses() {
        $parallelCurl = new ParallelCurl("testAgent");
        $this->assertTrue($parallelCurl->isClosed());
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php");        
        $this->assertFalse($parallelCurl->isClosed());
        $this->assertContains("AGENT: testAgent", $parallelCurl->getFirstResponse());
        $this->assertFalse($parallelCurl->isClosed());
        $parallelCurl->close();
        $this->assertTrue($parallelCurl->isClosed());
    }
    
    public function testDefaultAgent() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php");
        
        $this->assertContains("AGENT: php/parallelCurl", $parallelCurl->getFirstResponse());
        
        $parallelCurl->close();
    }
    
    public function testChangedAgent() {
        $parallelCurl = new ParallelCurl("testAgent");
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php");
        
        $this->assertContains("AGENT: testAgent", $parallelCurl->getFirstResponse());
        
        $parallelCurl->close();
    }
    
    public function testGet() {
        $parallelCurl = new ParallelCurl("testAgent");
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('a'=>'b'));
        
        $this->assertContains('GET: {"a":"b"}', $parallelCurl->getFirstResponse());
        
        $parallelCurl->close();
    }
    
    public function testPost() {
        $parallelCurl = new ParallelCurl("testAgent");
        $parallelCurl->addPostRequest("http://www.suchgenie.de/curlTest.php", array('a'=>'b'));
        
        $this->assertContains('POST: {"a":"b"}', $parallelCurl->getFirstResponse());
        
        $parallelCurl->close();
    }
    
    public function testGetAndPost() {
        $parallelCurl = new ParallelCurl("testAgent");
        $parallelCurl->addPostRequest("http://www.suchgenie.de/curlTest.php", array('a'=>'b'), array('c'=>'d'));
        
        $body = $parallelCurl->getFirstResponse();
        $this->assertContains('POST: {"a":"b"}', $body);
        $this->assertContains('GET: {"c":"d"}', $body);
        
        $parallelCurl->close();
    }
    
    public function testReopens() {
        $parallelCurl = new ParallelCurl("testAgent");        
        $this->assertTrue($parallelCurl->isClosed());
        
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php");        
        $this->assertFalse($parallelCurl->isClosed());
        $this->assertContains("AGENT: testAgent", $parallelCurl->getFirstResponse());
        $this->assertFalse($parallelCurl->isClosed());
        $parallelCurl->close();        
        $this->assertTrue($parallelCurl->isClosed());
        
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php");        
        $this->assertFalse($parallelCurl->isClosed());
        $this->assertContains("AGENT: testAgent", $parallelCurl->getFirstResponse());
        $this->assertFalse($parallelCurl->isClosed());
        $parallelCurl->close();   
        $this->assertTrue($parallelCurl->isClosed()); 
    }
    
    public function testDelayed() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('delay'=>3));
        
        $before = microtime(true);
        $this->assertContains("AGENT: php/parallelCurl", $parallelCurl->getFirstResponse());
        $after = microtime(true);
        $delay = $after - $before;
        
        $this->assertTrue($delay > 2.5);
        $this->assertTrue($delay < 3.5);
        
        $parallelCurl->close();
    }
    
    public function testFaster() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('delay'=>3));
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('delay'=>4));
        
        $before = microtime(true);
        $this->assertContains("sleeped for 3 sec", $parallelCurl->getFirstResponse());
        $after = microtime(true);
        $delay = $after - $before;
        
        $this->assertTrue($delay > 2.5);
        $this->assertTrue($delay < 3.5);
        
        $parallelCurl->close();
    }
    
    public function testDefaultTimeout() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('delay'=>12));
        
        $before = microtime(true);
        $body = $parallelCurl->getFirstResponse();
        $after = microtime(true);
        $delay = $after - $before;
        
        $this->assertNull($body);
        $this->assertTrue($delay > 9.5);
        $this->assertTrue($delay < 10.5);
        
        $parallelCurl->close();
    }
    
    public function testSetTimeout() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->setTimeout(4);
        $parallelCurl->addGetRequest("http://www.suchgenie.de/curlTest.php", array('delay'=>12));
        
        $before = microtime(true);
        $body = $parallelCurl->getFirstResponse();
        $after = microtime(true);
        $delay = $after - $before;
        
        $this->assertNull($body);
        $this->assertTrue($delay > 3.5);
        $this->assertTrue($delay < 4.5);
        
        $parallelCurl->close();
    }
    
    public function testFetchGoogle() {
        $parallelCurl = new ParallelCurl();
        $parallelCurl->addGetRequest("http://www.google.de/");
        $parallelCurl->addGetRequest("http://www.google.com/");
        
        $this->assertContains("google", $parallelCurl->getFirstResponse());
        
        $parallelCurl->close();
    }
}
