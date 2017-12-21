<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Data\File;
use De\Idrinth\ConfigCheck\Service\JsonFileValidator;

class JsonFileValidatorTest extends FileValidatorTest
{
    /**
     * @return JsonFileValidator
     */
    protected function getInstance()
    {
        return new JsonFileValidator();
    }

    /**
     * @param string $content
     * @return File
     */
    private function getValidFileMock($content) {
        $info = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\File')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $info->expects($this->any())
            ->method('getContent')
            ->willReturn($content);
        $info->expects($this->any())
            ->method('isFile')
            ->willReturn(true);
        $info->expects($this->any())
            ->method('getSize')
            ->willReturn(1);
        $info->expects($this->any())
            ->method('isReadable')
            ->willReturn(true);
        return $info;
    }

    /**
     * @return void
     */
    public function testCheckUnparsableJson()
    {
        $file = $this->getValidFileMock("broken");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\ConfigCheck\Message\ErrorMessage', $return[0], "broken files are not considered errors");
    }

    /**
     * @return void
     */
    public function testCheckNoObjectJson()
    {
        $file = $this->getValidFileMock("[\"\"]");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\ConfigCheck\Message\NoticeMessage', $return[0], "Json not being an object is not a notice");
    }

    /**
     * @return void
     */
    public function testCheckNoSchemaJson()
    {
        $file = $this->getValidFileMock("{\"\":\"\"}");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\ConfigCheck\Message\NoticeMessage', $return[0], "Lack of schema is not a notice");
    }
}