<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\XmlFileValidator;

class XmlFileValidatorTest extends FileValidatorTest
{
    /**
     * @return XmlFileValidator
     */
    protected function getInstance()
    {
        return new XmlFileValidator();
    }
}