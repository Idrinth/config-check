<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Service\IniFileValidator;

class IniFileValidatorTestCase extends FileValidatorTestCase
{

    /**
     * @return IniFileValidator
     */
    protected function getInstance()
    {
        return new IniFileValidator($this->getSchemaStoreMock());
    }

    /**
     * @return void
     */
    public function testCheckUnparsableIni()
    {
        $file = $this->getValidFileMock("[a]]\nzz=\n=\"\"q11");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            version_compare(PHP_VERSION, '5.6.1', '>=') ? 6 : 4,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[0],
            "broken files are not considered errors"
        );
    }

    /**
     * @return void
     */
    public function testCheckParsableIni()
    {
        $file = $this->getValidFileMock("[a]\nzz=11");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            0,
            $return,
            "there were more messages returned than expected"
        );
    }
}
