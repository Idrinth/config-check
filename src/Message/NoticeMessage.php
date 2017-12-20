<?php
namespace De\Idrinth\ConfigCheck\Message;

class NoticeMessage extends AbstractMessage
{
    public function isFailure($warningAsError = false)
    {
        return false;
    }

    protected function getSymbol()
    {
        return "i";
    }
}