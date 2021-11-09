<?php

namespace De\Idrinth\ConfigCheck\Service;

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
        $this->assertEquals(
            "qq",
            $instance->get('composer.json'),
            "Faked content was not returned."
        );
        $results = $retrievals->getCalled();
        $this->assertCount(
            1,
            $results,
            "there was an amount of functions used that didn't match 1"
        );
        $this->assertTrue(
            isset($results['fileGetContents']),
            "file get contents was not used"
        );
        $this->assertCount(
            1,
            $results['fileGetContents'],
            "there was an amount of function-calls used that didn't match 1"
        );
        $composerPath = realpath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'composer.json');
        $this->assertTrue(
            isset($results['fileGetContents'][$composerPath]),
            "composer path was not used to retrieve content."
        );
        $this->assertEquals(
            1,
            $results['fileGetContents'][$composerPath],
            "file get contents was called more than once for the path."
        );
    }

    /**
     * @test
     */
    public function testGetAbsolutePath()
    {
        $retrievals = new RetrievalFakes("qq");
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals(
            "qq",
            $instance->get(__FILE__),
            "Faked content was not returned."
        );
        $results = $retrievals->getCalled();
        $this->assertCount(
            1,
            $results,
            "there was an amount of functions used that didn't match 1"
        );
        $this->assertTrue(
            isset($results['fileGetContents']),
            "file get contents was not used"
        );
        $this->assertCount(
            1,
            $results['fileGetContents'],
            "there was an amount of function-calls used that didn't match 1"
        );
        $this->assertTrue(
            isset($results['fileGetContents'][__FILE__]),
            "file path was not used to retrieve content."
        );
        $this->assertEquals(
            1,
            $results['fileGetContents'][__FILE__],
            "file get contents was called more than once for the path."
        );
    }

    /**
     * @test
     */
    public function testGetRemoteByCurl()
    {
        $retrievals = new RetrievalFakes(false, "hello", true);
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals(
            "hello",
            $instance->get('https://getcomposer.org/schema.json'),
            "Faked content was not returned."
        );
        $results = $retrievals->getCalled();
        $this->assertCount(
            2,
            $results,
            "The amount of functions differs from the expected 2"
        );
        $this->assertTrue(isset($results['curlExec']), "curl wasn't used.");
        $this->assertCount(
            1,
            $results['curlExec'],
            "there was an amount of function-calls for curl used that didn't match 1"
        );
        $this->assertTrue(
            isset($results['curlExec']['resource']),
            "curl call was somehow not using a resource."
        );
        $this->assertEquals(
            1,
            $results['curlExec']['resource'],
            "curl was called more than once to retrieve content."
        );
        $this->assertTrue(
            isset($results['extensionLoaded']),
            "there was no check if the curl-exxtension is loaded."
        );
        $this->assertCount(
            1,
            $results['extensionLoaded'],
            "there was an amount of function-calls for extension loaded used that didn't match 1"
        );
    }

    /**
     * @test
     */
    public function testGetRemoteWithoutCurl()
    {
        $retrievals = new RetrievalFakes("hello", "", false);
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertEquals(
            "hello",
            $instance->get('https://getcomposer.org/schema.json'),
            "Faked content was not returned."
        );
        $results = $retrievals->getCalled();
        $this->assertCount(
            2,
            $results,
            "The amount of functions differs from the expected 2."
        );
        $this->assertTrue(
            isset($results['fileGetContents']),
            "file get contents wasn't used."
        );
        $this->assertCount(
            1,
            $results['fileGetContents'],
            "file get contents was used with multiple parameters."
        );
        $this->assertTrue(
            isset($results['fileGetContents']['https://getcomposer.org/schema.json']),
            "the expected url wasn't used."
        );
        $this->assertEquals(
            1,
            $results['fileGetContents']['https://getcomposer.org/schema.json'],
            "the expected url was used more than once."
        );
        $this->assertTrue(
            isset($results['extensionLoaded']),
            "there was no check if the curl-exxtension is loaded."
        );
        $this->assertCount(
            1,
            $results['extensionLoaded'],
            "there was an amount of function-calls for extension loaded used that didn't match 1"
        );
    }
}
