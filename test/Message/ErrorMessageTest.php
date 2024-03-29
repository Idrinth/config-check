<?php

namespace De\Idrinth\ConfigCheck\Message;

use De\Idrinth\ConfigCheck\Message\ErrorMessage;

class ErrorMessageTest extends AbstractMessageTest
{

    /**
     * @return void
     */
    public function testIsFailure()
    {
        $this->assertTrue($this->getInstance()->isFailure());
        $this->assertTrue($this->getInstance()->isFailure(true));
        $this->assertTrue($this->getInstance()->isFailure(false));
    }

    /**
     * @return ErrorMessage
     */
    protected function getInstance()
    {
        return new ErrorMessage("Example");
    }

    /**
     * @return int
     */
    protected function getMinVerbosity()
    {
        return 1;
    }
}
