<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\FileRetriever;
use PHPUnit\Framework\TestCase;

class FileRetrieverTest extends TestCase
{
    /**
     * @test
     */
    public function testGetRelativePath()
    {
        $retrievals = new RetrievalFakes("qq");
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals("qq", $instance->get('composer.json'));
        $results = $retrievals->getCalled();
        $this->assertCount(1, $results);
        $this->assertTrue(isset($results['fileGetContents']));
        $this->assertCount(1, $results['fileGetContents']);
        $composerPath = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'composer.json';
        $this->assertTrue(isset($results['fileGetContents'][$composerPath]));
        $this->assertEquals(1, $results['fileGetContents'][$composerPath]);
    }

    /**
     * @test
     */
    public function testGetAbsolutePath()
    {
        $retrievals = new RetrievalFakes("qq");
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals("qq", $instance->get(__FILE__));
        $results = $retrievals->getCalled();
        $this->assertCount(1, $results);
        $this->assertTrue(isset($results['fileGetContents']));
        $this->assertCount(1, $results['fileGetContents']);
        $this->assertTrue(isset($results['fileGetContents'][__FILE__]));
        $this->assertEquals(1, $results['fileGetContents'][__FILE__]);
    }

    /**
     * @test
     */
    public function testGetRemoteByCurl()
    {
        $retrievals = new RetrievalFakes(false, "hello", true);
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals("hello", $instance->get('https://getcomposer.org/schema.json'));
        $results = $retrievals->getCalled();
        $this->assertCount(2, $results);
        $this->assertTrue(isset($results['curlExec']));
        $this->assertCount(1, $results['curlExec']);
        $this->assertTrue(isset($results['curlExec']['resource']));
        $this->assertEquals(1, $results['curlExec']['resource']);
        $this->assertTrue(isset($results['extensionLoaded']));
        $this->assertCount(1, $results['extensionLoaded']);
    }

    /**
     * @test
     */
    public function testGetRemoteWithoutCurl()
    {
        $retrievals = new RetrievalFakes("hello", "", false);
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals("hello", $instance->get('https://getcomposer.org/schema.json'));
        $results = $retrievals->getCalled();
        $this->assertCount(2, $results);
        $this->assertTrue(isset($results['fileGetContents']));
        $this->assertCount(1, $results['fileGetContents']);
        $this->assertTrue(isset($results['fileGetContents']['https://getcomposer.org/schema.json']));
        $this->assertEquals(1, $results['fileGetContents']['https://getcomposer.org/schema.json']);
        $this->assertTrue(isset($results['extensionLoaded']));
        $this->assertCount(1, $results['extensionLoaded']);
    }
}
