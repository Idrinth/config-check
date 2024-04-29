<?php

namespace De\Idrinth\ConfigCheck\Message;

use De\Idrinth\ConfigCheck\Message;

abstract class AbstractMessage implements Message
{
    public function __construct(private string $message, private int $minVerbosity)
    {
        $this->message = trim($this->message, "\n ");
    }

    public function __toString(): string
    {
        return $this->toString(1);
    }

    public function toString(int $verbose = 0): string
    {
        if ($verbose < $this->minVerbosity) {
            return "";
        }
        if ($verbose === $this->minVerbosity) {
            return $this->getSymbol();
        }
        return "  [{$this->getSymbol()}] $this->message\n";
    }

    abstract protected function getSymbol(): string;
}
