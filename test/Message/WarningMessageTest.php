<?php

namespace De\Idrinth\JsonCheck\Test\Message;

use De\Idrinth\JsonCheck\Message\WarningMessage;

class WarningMessageTest extends AbstractMessageTest
{

    /**
     * @return void
     */
    public function testIsFailure()
    {
        $this->assertFalse($this->getInstance()->isFailure());
        $this->assertTrue($this->getInstance()->isFailure(true));
        $this->assertFalse($this->getInstance()->isFailure(false));
    }

    /**
     * @return WarningMessage
     */
    protected function getInstance()
    {
        return new WarningMessage("Example");
    }
}