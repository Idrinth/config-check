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
            6,
            $return,
            "there weren't 6 messages returned as expected"
        );
        $warnings = 0;
        $errors = 0;
        foreach ($return as $ret) {
            if ($ret instanceof \De\Idrinth\ConfigCheck\Message\ErrorMessage) {
                $errors++;
            } elseif ($ret instanceof \De\Idrinth\ConfigCheck\Message\WarningMessage) {
                $warnings++;
            }
        }
        $this->assertEquals(2, $warnings, "There were $warnings instead of 2 Warnings returned.");
        $this->assertEquals(4, $errors, "There were $errors instead of 4 Errors returned.");
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
