<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\IniFileValidator;

class IniFileValidatorTest extends FileValidatorTest
{

    /**
     * @return IniFileValidator
     */
    protected function getInstance()
    {
        return new IniFileValidator();
    }
}
