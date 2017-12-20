<?php
namespace De\Idrinth\ConfigCheck\Message;

class WarningMessage extends AbstractMessage
{
    public function isFailure($warningAsError = false)
    {
        return $warningAsError;
    }

    protected function getSymbol()
    {
        return "!";
    }
}