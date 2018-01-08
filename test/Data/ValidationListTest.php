<?php

namespace De\Idrinth\ConfigCheck\Test\Data;

use De\Idrinth\ConfigCheck\Data\ValidationList;
use PHPUnit\Framework\TestCase;

class ValidationListTest extends TestCase
{

    /**
     * @return void
     */
    public function testAddFile()
    {
        $instance = new ValidationList();
        $file = $this->getMockBuilder('\De\Idrinth\ConfigCheck\Data\FileResult')
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
        $this->assertCount(
            2,
            $result,
            "output does not provide expected element count"
        );
        $this->assertEquals(7, $result[0], "a list with 7 errors had none");
        $this->assertEquals(
            "\nConfig Check: Failed\n0/1 OK\n\n7 Errors\n",
            $result[1],
            "the list did not provide the predefined text"
        );
    }

    /**
     * @return void
     */
    public function testFinish()
    {
        $instance = new ValidationList();
        $result = $instance->finish();
        $this->assertCount(
            2,
            $result,
            "output does not provide expected element count"
        );
        $this->assertEquals(0, $result[0], "an empty list had errors");
        $this->assertEquals(
            "\nConfig Check: OK\n0/0 OK\n\n\n",
            $result[1],
            "an empty list had a different than expected text text"
        );
    }
}
