<?php

namespace De\Idrinth\ConfigCheck\Test\Data;

use De\Idrinth\ConfigCheck\Data\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     */
    public function testIsEnabled()
    {
        $config = new Config();
        foreach(array('yml','xml','ini','json') as $extension) {
            $this->assertTrue($config->isEnabled($extension), "$extension is not enabled by default.");
        }
    }

    /**
     */
    public function testGetBlacklist()
    {
        $config = new Config();
        foreach(array('yml','xml','ini','json') as $extension) {
            $this->assertCount(1, $config->getBlacklist($extension), "$extension has an unexpected default ignore list.");
            $this->assertEquals("vendor", array_pop($config->getBlacklist($extension)), "$extension does not ignore vendor as expected.");
        }
    }
}