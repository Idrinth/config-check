<?php

namespace De\Idrinth\ConfigCheck\Message;

class NoticeMessage extends AbstractMessage
{
    public function __construct(string $message)
    {
        parent::__construct($message, 3);
    }

    public function isFailure(bool $warningAsError = false): bool
    {
        return false;
    }

    protected function getSymbol(): string
    {
        return "i";
    }
}
