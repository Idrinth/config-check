<?php

namespace De\Idrinth\ConfigCheck\Message;

use De\Idrinth\ConfigCheck\Message\NoticeMessage;

class NoticeMessageTestCase extends AbstractMessageTestCase
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

    /**
     * @return int
     */
    protected function getMinVerbosity()
    {
        return 3;
    }
}
