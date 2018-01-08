<?php

namespace De\Idrinth\ConfigCheck\Message;

class ErrorMessage extends AbstractMessage
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 1);
    }

    /**
     * @param boolean $warningAsError
     * @return boolean
     */
    public function isFailure($warningAsError = false)
    {
        return true;
    }
    /**
     * @return string
     */
    protected function getSymbol()
    {
        return "-";
    }
}
