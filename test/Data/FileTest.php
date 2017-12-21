<?php

namespace De\Idrinth\ConfigCheck\Test\Data;

use De\Idrinth\ConfigCheck\Data\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /**
     */
    public function testIsFile()
    {
        $instance = new File(__FILE__);
        $this->assertTrue($instance->isFile(), "the file running is not considered a file.");
        $instance2 = new File(__FILE__.'.oo');
        $this->assertFalse($instance2->isFile(), "a missing file is a file.");
    }

    /**
     */
    public function testIsReadable()
    {
        $instance = new File(__FILE__);
        $this->assertTrue($instance->isReadable(), "the file running is not considered readable.");
        $instance2 = new File(__FILE__.'.oo');
        $this->assertFalse($instance2->isReadable(), "a missing file is readable.");
    }

    /**
     */
    public function testGetSize()
    {
        $instance = new File(__FILE__);
        $this->assertGreaterThan(0, $instance->getSize(), "the file running is considered empty.");
        $instance2 = new File(__FILE__.'.oo');
        $this->assertEquals(0, $instance2->getSize(), "a missing file has a size above zero.");
    }

    /**
     */
    public function testGetContent()
    {
        $instance = new File(__FILE__);
        $this->assertNotEmpty($instance->getContent(), "the file running is considered empty.");
        $instance2 = new File(__FILE__.'.oo');
        $this->assertEmpty($instance2->getContent(), "a missing file has content.");
    }
}