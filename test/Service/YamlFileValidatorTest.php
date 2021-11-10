<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Service\YamlFileValidator;
use JsonSchema\Validator;

class YamlFileValidatorTest extends FileValidatorTest
{

    /**
     * @return YamlFileValidator
     */
    protected function getInstance()
    {
        return new YamlFileValidator($this->getSchemaStoreMock(), $this->getMockBuilder(Validator::class)->getMock());
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
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[0],
            "broken files are not considered errors"
        );
    }

    /**
     * @return void
     */
    public function testCheckParsableYaml()
    {
        $file = $this->getValidFileMock("broken:\n  - a:o\n  - b: u");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were more messages returned than expected");
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\NoticeMessage',
            $return[0],
            "missing validation is not considered a notice"
        );
    }
}
