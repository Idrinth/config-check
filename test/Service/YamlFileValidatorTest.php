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
    /**
     * @return void
     */
    public function testCheckUnparsableYaml()
    {
        $file = $this->getValidFileMock("broken:\n  - a: o\n- qq\n  b: u");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\ConfigCheck\Message\ErrorMessage', $return[0], "broken files are not considered errors");
    }
    /**
     * @return void
     */
    public function testCheckParsableYaml()
    {
        $file = $this->getValidFileMock("broken:\n  - a:o\n  - b: u");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(0, $return, "there were more messages returned than expected");
    }
}
