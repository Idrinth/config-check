<?php

namespace De\Idrinth\ConfigCheck\Message;

class WarningMessage extends AbstractMessage
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 2);
    }

    /**
     * @param boolean $warningAsError
     * @return boolean
     */
    public function isFailure($warningAsError = false)
    {
        return $warningAsError;
    }

    /**
     * @return string
     */
    protected function getSymbol()
    {
        return "!";
    }
}
