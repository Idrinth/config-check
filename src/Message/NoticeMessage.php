<?php
namespace De\Idrinth\JsonCheck\Message;

class NoticeMessage extends AbstractMessage
{
    public function isFailure($warningAsError = false)
    {
        return false;
    }

    protected function getSymbol()
    {
        return "X";
    }
}