<?php

namespace De\Idrinth\ConfigCheck\TestData;

use De\Idrinth\ConfigCheck\Data\FileResult;
use PHPUnit\Framework\TestCase;

class FileResultTest extends TestCase
{

    /**
     * @test
     */
    public function testAddGetMessageError()
    {
        $this->internalGetMessage(
            array(
                array('', ''),//verbosity 0
                array("\n[F] " . __DIR__ . "\n1", "\n[F] " . __DIR__ . "\n1"),//verbosity 1
                array("\n[F] " . __DIR__ . "\n2", "\n[F] " . __DIR__ . "\n2"),//verbosity 2
                array("\n[F] " . __DIR__ . "\n3", "\n[F] " . __DIR__ . "\n3"),//verbosity 3
            ),
            $this->getMockedMessage(true)
        );
    }

    /**
     * @test
     */
    public function testAddGetMessageWarning()
    {
        $this->internalGetMessage(
            array(
                array('', ''),//verbosity 0
                array('', "\n[F] " . __DIR__ . "\n1"),//verbosity 1
                array('', "\n[F] " . __DIR__ . "\n2"),//verbosity 2
                array("\n[X] " . __DIR__ . "\n3", "\n[F] " . __DIR__ . "\n3"),//verbosity 3
            ),
            $this->getMockedMessage()
        );
    }

    /**
     * @test
     */
    public function testAddGetMessageNotice()
    {
        $this->internalGetMessage(
            array(
                array('', ''),//verbosity 0
                array('', ""),//verbosity 1
                array('', ""),//verbosity 2
                array("\n[X] " . __DIR__ . "\n3", "\n[X] " . __DIR__ . "\n3"),//verbosity 3
            ),
            $this->getMockedMessage(false)
        );
    }

    /**
     * @test
     */
    public function testAddGetMessageEmpty()
    {
        $this->internalGetMessage(array(
            array('', ''),//verbosity 0
            array('', ''),//verbosity 1
            array('', ''),//verbosity 2
            array("\n[X] " . __DIR__ . "\n", "\n[X] " . __DIR__ . "\n")//verbosity 3
        ));
    }

    /**
     * @param array $asserts the expected results
     * @param Message|Null $message
     */
    private function internalGetMessage($asserts = array(), $message = null)
    {
        $instance = new FileResult(__DIR__);
        if ($message) {
            $instance->addMessage($message);
        }
        $this->assertEquals(
            $asserts[1][0],
            $instance . "",
            "__toString failed"
        );
        $this->assertEquals(
            $asserts[0][0],
            $instance->getMessage(),
            "getMessage() failed"
        );
        $this->assertEquals(
            $asserts[0][0],
            $instance->getMessage(0),
            "getMessage(0) failed"
        );
        $this->assertEquals(
            $asserts[0][1],
            $instance->getMessage(0, true),
            "getMessage(0, true) failed"
        );
        $this->assertEquals(
            $asserts[1][0],
            $instance->getMessage(1),
            "getMessage(1) failed"
        );
        $this->assertEquals(
            $asserts[1][1],
            $instance->getMessage(1, true),
            "getMessage(1, true) failed"
        );
        $this->assertEquals(
            $asserts[2][0],
            $instance->getMessage(2),
            "getMessage(2) failed"
        );
        $this->assertEquals(
            $asserts[2][1],
            $instance->getMessage(2, true),
            "getMessage(2, true) failed"
        );
        $this->assertEquals(
            $asserts[3][0],
            $instance->getMessage(3),
            "getMessage(3) failed"
        );
        $this->assertEquals(
            $asserts[3][1],
            $instance->getMessage(3, true),
            "getMessage(3, true) failed"
        );
    }

    /**
     * @test
     */
    public function testGetErrorNum()
    {
        $instance = new FileResult(__DIR__);
        $this->assertEquals(
            0,
            $instance->getErrorNum(),
            "empty list is not considered empty with default"
        );
        $this->assertEquals(
            0,
            $instance->getErrorNum(false),
            "empty list is not considered empty with false"
        );
        $this->assertEquals(
            0,
            $instance->getErrorNum(true),
            "empty list is not considered empty with true"
        );
        $instance->addMessage($this->getMockedMessage(false));
        $this->assertEquals(
            0,
            $instance->getErrorNum(),
            "one notice list is not considered empty with default"
        );
        $this->assertEquals(
            0,
            $instance->getErrorNum(false),
            "one notice list is not considered empty with false"
        );
        $this->assertEquals(
            0,
            $instance->getErrorNum(true),
            "one notice list is not considered empty with true"
        );
        $instance->addMessage($this->getMockedMessage(true));
        $this->assertEquals(
            1,
            $instance->getErrorNum(),
            "notice and error list does not have expected size of 1 with default"
        );
        $this->assertEquals(
            1,
            $instance->getErrorNum(false),
            "notice and error list does not have expected size of 1 with false"
        );
        $this->assertEquals(
            1,
            $instance->getErrorNum(true),
            "notice and error list does not have expected size of 1 with true"
        );
        $instance->addMessage($this->getMockedMessage());
        $this->assertEquals(
            1,
            $instance->getErrorNum(),
            "notice, warning and error list does not have expected size of 1 with default"
        );
        $this->assertEquals(
            1,
            $instance->getErrorNum(false),
            "notice, warning and error list does not have expected size of 1 with false"
        );
        $this->assertEquals(
            2,
            $instance->getErrorNum(true),
            "notice, warning and error list does not have expected size of 2 with true"
        );
    }

    /**
     * @param null|boolean $return
     * @return Message (mocked)
     */
    private function getMockedMessage($return = null)
    {
        $message = $this->getMockedBaseMessage();
        if ($return === null) {
            $message->expects($this->any())
                ->method("isFailure")
                ->willReturnArgument(0);
            return $message;
        }
        $message->expects($this->any())
            ->method("isFailure")
            ->willReturn($return);
        return $message;
    }

    /**
     * @return Message (mocked)
     */
    private function getMockedBaseMessage()
    {
        $message = $this->getMockBuilder('\De\Idrinth\ConfigCheck\Message')->getMock();
        $message->expects($this->any())
            ->method("__toString")
            ->willReturn("_");
        $message->expects($this->any())
            ->method("toString")
            ->willReturnArgument(0);
        return $message;
    }
}
