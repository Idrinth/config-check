<?php
namespace De\Idrinth\ConfigCheck\Message;

class ErrorMessage extends AbstractMessage
{
    public function isFailure($warningAsError = false)
    {
        return true;
    }

    protected function getSymbol()
    {
        return "-";
    }
}