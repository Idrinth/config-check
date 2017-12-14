<?php

namespace De\Idrinth\JsonCheck\Test\Message;

use De\Idrinth\JsonCheck\Message\NoticeMessage;

class NoticeMessageTest extends AbstractMessageTest
{

    /**
     * @return void
     */
    public function testIsFailure()
    {
        $this->assertFalse($this->getInstance()->isFailure());
        $this->assertFalse($this->getInstance()->isFailure(true));
        $this->assertFalse($this->getInstance()->isFailure(false));
    }

    /**
     * @return NoticeMessage
     */
    protected function getInstance()
    {
        return new NoticeMessage("Example");
    }
}