<?php

namespace De\Idrinth\ConfigCheck\Test\Data;

use De\Idrinth\ConfigCheck\Data\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    /**
     * @test
     */
    public function testIsEnabled()
    {
        $config = new Config(__DIR__, array());
        foreach (array('yml', 'xml', 'ini', 'json') as $extension) {
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
        $config = new Config(__DIR__, array());
        foreach (array('yml', 'xml', 'ini', 'json') as $extension) {
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
        $config1 = new Config(__DIR__, array());
        $this->assertEquals(__DIR__, $config1->getRootDir(), "Root dir doesn't match.");
        $config2 = new Config(dirname(__DIR__), array());
        $this->assertEquals(dirname(__DIR__), $config2->getRootDir(), "Root dir doesn't match.");
    }

    /**
     * @test
     */
    public function testGetVerbosity()
    {
        $config1 = new Config(__DIR__, array());
        $this->assertEquals(0, $config1->getVerbosity(), "Default verbosity is not 0.");
        $config2 = new Config(__DIR__, array('v' => null));
        $this->assertEquals(1, $config2->getVerbosity(), "Verbosity is not 1.");
        $config3 = new Config(__DIR__, array('v' => array()));
        $this->assertEquals(0, $config3->getVerbosity(), "Default verbosity is not 0.");
        $config4 = new Config(__DIR__, array('v' => array(1,2,2)));
        $this->assertEquals(3, $config4->getVerbosity(), "Verbosity is not 3.");
    }

    /**
     * @return boolean
     */
    public function testHasWarningsAsErrors()
    {
        $config1 = new Config(__DIR__, array());
        $this->assertFalse($config1->hasWarningsAsErrors(), "Warnings as Errors was enabled by default.");
        $config2 = new Config(__DIR__, array("w" => array()));
        $this->assertTrue($config2->hasWarningsAsErrors(), "Warnings as Errors was enabled by empty array.");
        $config3 = new Config(__DIR__, array("w" => null));
        $this->assertTrue($config3->hasWarningsAsErrors(), "Warnings as Errors was not enabled by null.");
        $config4 = new Config(__DIR__, array("w" => array(1)));
        $this->assertTrue($config4->hasWarningsAsErrors(), "Warnings as Errors was not enabled.");
        $config5 = new Config(__DIR__, array("w" => array(1,2,3)));
        $this->assertTrue($config5->hasWarningsAsErrors(), "Warnings as Errors was not enabled by multiple values.");
    }
}
