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

    /**
     * @test
     */
    public function testCheckUnparsableXml()
    {
        $file = $this->getValidFileMock("<<xml>");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            3,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[0],
            "broken files are not considered errors(1)"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[1],
            "broken files are not considered errors(2)"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[2],
            "broken files are not considered errors(3)"
        );
    }

    /**
     * @test
     */
    public function testCheckParsableXml()
    {
        $file = $this->getValidFileMock("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root><my /></root>");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            0,
            $return,
            "there were more messages returned than expected"
        );
    }
}
