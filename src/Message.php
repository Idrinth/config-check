<?php

namespace De\Idrinth\ConfigCheck;

interface Message
{

    /**
     * @param int $verbose
     * @return string
     */
    public function toString($verbose = 0);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param boolean $warningAsError
     * @return boolean
     */
    public function isFailure($warningAsError = false);
}
