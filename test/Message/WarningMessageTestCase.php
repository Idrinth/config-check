<?php

namespace De\Idrinth\ConfigCheck\Message;

use De\Idrinth\ConfigCheck\Message\WarningMessage;

class WarningMessageTestCase extends AbstractMessageTestCase
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

    /**
     * @return int
     */
    protected function getMinVerbosity()
    {
        return 2;
    }
}
