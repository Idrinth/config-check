<?php

namespace De\Idrinth\JsonCheck\Test\Message;

use De\Idrinth\JsonCheck\Message\AbstractMessage;
use PHPUnit\Framework\TestCase;

abstract class AbstractMessageTest extends TestCase
{
    /**
     * @return AbstractMessage
     */
    abstract protected function getInstance();

    /**
     * @return void
     */
    public function testToString()
    {
        $this->assertEquals("", $this->getInstance()->toString());
        $this->assertEquals("", $this->getInstance()->__toString());
        $this->assertEquals(1, strlen($this->getInstance()->toString(1)));
        $this->assertEquals(12, strlen($this->getInstance()->toString(2)));
    }
}