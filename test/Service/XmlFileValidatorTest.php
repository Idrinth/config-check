<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Service\XmlFileValidator;

class XmlFileValidatorTest extends FileValidatorTest
{

    /**
     * @return XmlFileValidator
     */
    protected function getInstance()
    {
        return new XmlFileValidator($this->getSchemaStoreMock());
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
            6,
            $return,
            "there weren't 6 messages returned as expected"
        );
        $errors = 0;
        foreach ($return as $ret) {
            if ($ret instanceof ErrorMessage) {
                $errors++;
            }
        }
        $this->assertEquals(6, $errors, "There were $errors instead of 6 Errors returned.");
    }

    /**
     * @test
     */
    public function testCheckBrokenDTDXml()
    {
        $file = $this->getValidFileMock(
            "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            . "<!DOCTYPE EXAMPLE [\n<!ELEMENT rotten (#PCDATA)>\n]>\n"
            . "<root><my /></root>"
        );
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            2,
            $return,
            "there weren't 2 messages returned as expected"
        );
        $errors = 0;
        foreach ($return as $ret) {
            if ($ret instanceof ErrorMessage) {
                $errors++;
            }
        }
        $this->assertEquals(2, $errors, "There were $errors instead of 2 Errors returned.");
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
            1,
            $return,
            "there was an unexpected number of messages returned"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\NoticeMessage',
            $return[0],
            "The message was of an unexpected type: " . get_class($return[0])
        );
    }
}
