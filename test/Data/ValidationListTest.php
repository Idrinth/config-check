<?php

namespace De\Idrinth\JsonCheck\Test\Data;

use De\Idrinth\JsonCheck\Data\ValidationList;
use PHPUnit\Framework\TestCase;

class ValidationListTest extends TestCase
{
    /**
     * @return void
     */
    public function testAddFile()
    {
        $instance = new ValidationList();
        $file = $this->getMockBuilder('\De\Idrinth\JsonCheck\Data\JsonFileResult')
            ->setConstructorArgs(array(''))
            ->getMock();
        $file->expects($this->any())
            ->method("getErrorNum")
            ->willReturn(7);
        $file->expects($this->any())
            ->method("getMessage")
            ->willReturn("7 Errors");
        $instance->addFile($file);
        $result = $instance->finish();
        $this->assertCount(2, $result, "output does not provide expected element count");
        $this->assertEquals(7, $result[0], "a list with 7 errors had none");
        $this->assertEquals("7 Errors", $result[1], "the list did not provide the predefined text");
    }

    /**
     * @return void
     */
    public function testFinish()
    {
        $instance = new ValidationList();
        $result = $instance->finish();
        $this->assertCount(2, $result, "output does not provide expected element count");
        $this->assertEquals(0, $result[0], "an empty list had errors");
        $this->assertEquals("", $result[1], "an empty list had text");
    }
}