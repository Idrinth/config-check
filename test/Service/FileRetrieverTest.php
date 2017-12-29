<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\FileRetriever;
use PHPUnit\Framework\TestCase;

class FileRetrieverTest extends TestCase
{
    /**
     * @test
     */
    public function testGet()
    {
        $instance = new FileRetriever(dirname(dirname(__DIR__)));
        $this->assertNotEmpty($instance->get('composer.json'));
        $this->assertNotEmpty($instance->get(__FILE__));
        $this->assertNotEmpty($instance->get('https://getcomposer.org/schema.json'));
    }
}
