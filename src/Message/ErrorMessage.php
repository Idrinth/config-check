<?php

namespace De\Idrinth\ConfigCheck\Message;

class ErrorMessage extends AbstractMessage
{
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
