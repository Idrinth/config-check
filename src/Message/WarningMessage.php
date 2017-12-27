<?php

namespace De\Idrinth\ConfigCheck\Message;

class WarningMessage extends AbstractMessage
{

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
