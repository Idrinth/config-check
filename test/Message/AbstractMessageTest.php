<?php

namespace De\Idrinth\ConfigCheck\Test\Message;

use De\Idrinth\ConfigCheck\Message\AbstractMessage;
use PHPUnit\Framework\TestCase;

abstract class AbstractMessageTest extends TestCase
{

    /**
     * @return AbstractMessage
     */
    abstract protected function getInstance();

    /**
     * @return int
     */
    abstract protected function getMinVerbosity();

    /**
     * @return void
     */
    public function testToString()
    {
        $this->assertEquals(
            $this->getInstance()->toString(0),
            $this->getInstance()->toString(),
            "verbosity does not default to 0"
        );
        $this->assertEquals(
            $this->getInstance()->toString(1),
            $this->getInstance()->__toString(),
            "__toString does not use verbosity 1 by default"
        );
        for ($verbosity = 0; $verbosity < 4; $verbosity++) {
            $expectedLength = 0;
            if ($verbosity === $this->getMinVerbosity()) {
                $expectedLength = 1;
            } elseif ($verbosity > $this->getMinVerbosity()) {
                $expectedLength = 14;
            }
            $this->assertEquals(
                $expectedLength,
                strlen($this->getInstance()->toString($verbosity)),
                "At verbosity $verbosity the string was not $expectedLength characters long."
            );
        }
    }
}
