<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\YamlFileValidator;

class YamlFileValidatorTest extends FileValidatorTest
{

    /**
     * @return YamlFileValidator
     */
    protected function getInstance()
    {
        return new YamlFileValidator();
    }
}
