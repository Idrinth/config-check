<?php

namespace De\Idrinth\ConfigCheck\Data;

use De\Idrinth\ConfigCheck\Data\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    /**
     * @test
     */
    public function testIsEnabled()
    {
        $config = new Config(__DIR__, []);
        foreach (array('yaml', 'xml', 'ini', 'json') as $extension) {
            $this->assertTrue(
                $config->isEnabled($extension),
                "$extension is not enabled by default."
            );
        }
    }

    /**
     * @test
     */
    public function testGetBlacklist()
    {
        $config = new Config(__DIR__, []);
        foreach (array('yaml', 'xml', 'ini', 'json') as $extension) {
            $results = $config->getBlacklist($extension);
            $this->assertCount(
                1,
                $results,
                "$extension has an unexpected default ignore list."
            );
            $this->assertEquals(
                "/vendor",
                $results[0],
                "$extension does not ignore vendor as expected."
            );
        }
    }

    /**
     * @test
     */
    public function testGetRootDir()
    {
        $config1 = new Config(__DIR__, []);
        $this->assertEquals(__DIR__, $config1->getRootDir(), "Root dir doesn't match.");
        $config2 = new Config(dirname(__DIR__), []);
        $this->assertEquals(dirname(__DIR__), $config2->getRootDir(), "Root dir doesn't match.");
    }

    /**
     * @test
     */
    public function testGetVerbosity()
    {
        $config1 = new Config(__DIR__, []);
        $this->assertEquals(0, $config1->getVerbosity(), "Default verbosity is not 0.");
        $config2 = new Config(__DIR__, array('v' => null));
        $this->assertEquals(1, $config2->getVerbosity(), "Verbosity is not 1.");
        $config3 = new Config(__DIR__, array('v' => []));
        $this->assertEquals(0, $config3->getVerbosity(), "Default verbosity is not 0.");
        $config4 = new Config(__DIR__, array('v' => array(1,2,2)));
        $this->assertEquals(3, $config4->getVerbosity(), "Verbosity is not 3.");
    }

    /**
     * @test
     */
    public function testHasWarningsAsErrors()
    {
        $config1 = new Config(__DIR__, []);
        $this->assertFalse($config1->hasWarningsAsErrors(), "Warnings as Errors was enabled by default.");
        $config2 = new Config(__DIR__, array("w" => []));
        $this->assertTrue($config2->hasWarningsAsErrors(), "Warnings as Errors was enabled by empty array.");
        $config3 = new Config(__DIR__, array("w" => null));
        $this->assertTrue($config3->hasWarningsAsErrors(), "Warnings as Errors was not enabled by null.");
        $config4 = new Config(__DIR__, array("w" => array(1)));
        $this->assertTrue($config4->hasWarningsAsErrors(), "Warnings as Errors was not enabled.");
        $config5 = new Config(__DIR__, array("w" => array(1,2,3)));
        $this->assertTrue($config5->hasWarningsAsErrors(), "Warnings as Errors was not enabled by multiple values.");
    }

    /**
     * @test
     */
    public function testGetMapping()
    {
        $config = new Config(__DIR__, []);
        $this->assertIsArray($config->getMapping('ini'), 'INI-Mapping was not an array');
        $this->assertCount(0, $config->getMapping('xml'), 'XML-Mapping was filled');
        $this->assertCount(0, $config->getMapping('json'), 'JSON-Mapping was filled');
    }

    /**
     * @test
     */
    public function testReadFromFile()
    {
        $root = dirname(dirname(__DIR__));
        $config = new Config($root, []);
        $config1 = new Config($root, array("v" => array(1, 2,3)));
        $this->assertFalse($config->hasWarningsAsErrors(), "Warnings as Errors was enabled by default.");
        $this->assertEquals(2, $config->getVerbosity(), "Verbosity did not match the expected value of 2.");
        $this->assertEquals(3, $config1->getVerbosity(), "Verbosity did not match the expected value of 3.");
        $this->assertEquals($root, $config->getRootDir(), "The root dir was not stored as expected.");
        $this->assertTrue($config->isEnabled("json"), "Json was not enabled.");
        $this->assertTrue($config->isEnabled("xml"), "Xml was enabled.");
        $this->assertCount(2, $config->getMapping('json'), 'JSON-Mapping was not filled');
        $this->assertArrayHasKey('.idrinth-cc.json', $config->getMapping('json'), 'JSON-Mapping was not filled');
        $this->assertArrayHasKey('composer.json', $config->getMapping('json'), 'JSON-Mapping was not filled');
        $jsonBlackList = $config->getBlacklist("json");
        $this->assertCount(3, $jsonBlackList, "The json blacklist did not have the expected elements.");
        $this->assertEquals("/vendor", $jsonBlackList[0], "The local vendor folder was not ignored.");
        $this->assertEquals("/test", $jsonBlackList[1], "The local test folder was not ignored.");
        $this->assertEquals("broken.json", $jsonBlackList[2], "Intentionally broken files are not ignored.");
        $xmlBlackList = $config->getBlacklist("xml");
        $this->assertCount(2, $xmlBlackList, "The xml blacklist did not have the expected elements.");
        $this->assertEquals("/vendor", $xmlBlackList[0], "The local vendor folder was not ignored.");
        $this->assertEquals("/test", $xmlBlackList[1], "The local test folder was not ignored.");
    }
}
