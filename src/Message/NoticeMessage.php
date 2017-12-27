<?php

namespace De\Idrinth\ConfigCheck\Message;

class NoticeMessage extends AbstractMessage
{

    /**
     * @param boolean $warningAsError
     * @return boolean
     */
    public function isFailure($warningAsError = false)
    {
        return false;
    }

    /**
     * @return string
     */
    protected function getSymbol()
    {
        return "i";
    }
}
