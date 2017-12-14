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
        $this->assertEquals($title, $instance."");
        $this->assertEquals($title, $instance->getMessage());
        $this->assertEquals($title, $instance->getMessage(0));
        $this->assertEquals($title, $instance->getMessage(1));
        $this->assertEquals($title, $instance->getMessage(2));
        $instance->addMessage($this->getMockedMessage(false));
        $this->assertEquals($title."0", $instance."");
        $this->assertEquals($title."0", $instance->getMessage());
        $this->assertEquals($title."0", $instance->getMessage(0));
        $this->assertEquals($title."1", $instance->getMessage(1));
        $this->assertEquals($title."2", $instance->getMessage(2));
    }

    /**
     * @creturn null
     */
    public function testGetErrorNum()
    {
        $instance = new JsonFileResult(__DIR__);
        $this->assertEquals(0, $instance->getErrorNum());
        $this->assertEquals(0, $instance->getErrorNum(true));
        $instance->addMessage($this->getMockedMessage(false));
        $this->assertEquals(0, $instance->getErrorNum());
        $this->assertEquals(0, $instance->getErrorNum(true));
        $instance->addMessage($this->getMockedMessage(true));
        $this->assertEquals(1, $instance->getErrorNum());
        $this->assertEquals(1, $instance->getErrorNum(true));
        $instance->addMessage($this->getMockedMessage());
        $this->assertEquals(1, $instance->getErrorNum());
        $this->assertEquals(2, $instance->getErrorNum(true));
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