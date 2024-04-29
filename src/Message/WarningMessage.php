<?php

namespace De\Idrinth\ConfigCheck\Message;

class WarningMessage extends AbstractMessage
{
    public function __construct(string $message)
    {
        parent::__construct($message, 2);
    }

    public function isFailure(bool $warningAsError = false): bool
    {
        return $warningAsError;
    }

    protected function getSymbol(): string
    {
        return "!";
    }
}
