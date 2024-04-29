<?php

namespace De\Idrinth\ConfigCheck;

interface Message
{
    public function toString(int $verbose = 0);
    public function __toString(): string;
    public function isFailure(bool $warningAsError = false): bool;
}
