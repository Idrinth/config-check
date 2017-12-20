<?php
namespace De\Idrinth\ConfigCheck;

interface Message
{
    public function toString($verbose = 0);
    public function __toString();
    public function isFailure($warningAsError = false);
}