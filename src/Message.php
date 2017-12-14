<?php
namespace De\Idrinth\JsonCheck;

interface Message
{
    public function toString($verbose = 0);
    public function __toString();
    public function isFailure($warningAsError = false);
}