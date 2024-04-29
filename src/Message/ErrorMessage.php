<?php

namespace De\Idrinth\ConfigCheck\Message;

class ErrorMessage extends AbstractMessage
{
    public function __construct(string $message)
    {
        parent::__construct($message, 1);
    }

    public function isFailure(bool $warningAsError = false): bool
    {
        return true;
    }

    protected function getSymbol(): string
    {
        return "-";
    }
}
