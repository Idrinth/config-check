<?php

namespace De\Idrinth\JsonCheck\TestData;

use De\Idrinth\JsonCheck\Data\JsonFileResult;
use PHPUnit\Framework\TestCase;

class JsonFileResultTest extends TestCase
{
    /**
     * @return void
     */
    public function testAddGetMessage()
    {
        $instance = new JsonFileResult(__DIR__);
        $title = "\n".__DIR__."\n";
        $this->assertEquals($title, $instance."", "__toString of empty list failed");
        $this->assertEquals($title, $instance->getMessage(), "getMessage() of empty list failed");
        $this->assertEquals($title, $instance->getMessage(0), "getMessage(0) of empty list failed");
        $this->assertEquals($title, $instance->getMessage(1), "getMessage(1) of empty list failed");
        $this->assertEquals($title, $instance->getMessage(2), "getMessage(2) of empty list failed");
        $instance->addMessage($this->getMockedMessage(false));
        $this->assertEquals($title."0", $instance."", "__toString of filled list failed");
        $this->assertEquals($title."0", $instance->getMessage(), "getMessage() of filled list failed");
        $this->assertEquals($title."0", $instance->getMessage(0), "getMessage(0) of filled list failed");
        $this->assertEquals($title."1", $instance->getMessage(1), "getMessage(1) of filled list failed");
        $this->assertEquals($title."2", $instance->getMessage(2), "getMessage(2) of filled list failed");
    }

    /**
     * @creturn null
     */
    public function testGetErrorNum()
    {
        $instance = new JsonFileResult(__DIR__);
        $this->assertEquals(0, $instance->getErrorNum(), "empty list is not considered empty with default");
        $this->assertEquals(0, $instance->getErrorNum(false), "empty list is not considered empty with false");
        $this->assertEquals(0, $instance->getErrorNum(true), "empty list is not considered empty with true");
        $instance->addMessage($this->getMockedMessage(false));
        $this->assertEquals(0, $instance->getErrorNum(), "one notice list is not considered empty with default");
        $this->assertEquals(0, $instance->getErrorNum(false), "one notice list is not considered empty with false");
        $this->assertEquals(0, $instance->getErrorNum(true), "one notice list is not considered empty with true");
        $instance->addMessage($this->getMockedMessage(true));
        $this->assertEquals(1, $instance->getErrorNum(), "notice and error list does not have expected size of 1 with default");
        $this->assertEquals(1, $instance->getErrorNum(false), "notice and error list does not have expected size of 1 with false");
        $this->assertEquals(1, $instance->getErrorNum(true), "notice and error list does not have expected size of 1 with true");
        $instance->addMessage($this->getMockedMessage());
        $this->assertEquals(1, $instance->getErrorNum(), "notice, warning and error list does not have expected size of 1 with default");
        $this->assertEquals(1, $instance->getErrorNum(false), "notice, warning and error list does not have expected size of 1 with false");
        $this->assertEquals(2, $instance->getErrorNum(true), "notice, warning and error list does not have expected size of 2 with true");
    }

    /**
     * @param null|boolean $return
     * @return Message (mocked)
     */
    private function getMockedMessage($return = null) {
        $message = $this->getMock('\De\Idrinth\JsonCheck\Message');
        if($return === null) {
            $message->expects($this->any())
                ->method("isFailure")
                ->willReturnArgument(0);
        } else {
            $message->expects($this->any())
                ->method("isFailure")
                ->willReturn($return);
        }
        $message->expects($this->any())
            ->method("__toString")
            ->willReturn("_");
        $message->expects($this->any())
            ->method("toString")
            ->willReturnArgument(0);
        return $message;
    }
}