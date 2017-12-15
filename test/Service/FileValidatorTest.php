<?php

namespace De\Idrinth\JsonCheck\Test\Service;

use PHPUnit\Framework\TestCase;

class FileValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testCheckNotFile()
    {
        $file = $this->getMockBuilder('SplFileObject')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
             ->method('isFile')
            ->willReturn(false);
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\WarningMessage', $return[0], "empty files are not warnings");
    }

    /**
     * @return void
     */
    public function testCheckEmptyFile()
    {
        $file = $this->getMockBuilder('SplFileObject')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
             ->method('isFile')
            ->willReturn(true);
        $file->expects($this->any())
             ->method('getSize')
            ->willReturn(0);
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\WarningMessage', $return[0], "empty files are not warnings");
    }

    /**
     * @return void
     */
    public function testCheckUnreadableFile()
    {
        $file = $this->getMockBuilder('SplFileObject')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
             ->method('isFile')
            ->willReturn(true);
        $file->expects($this->any())
             ->method('getSize')
            ->willReturn(1);
        $file->expects($this->any())
             ->method('isReadable')
            ->willReturn(false);
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\ErrorMessage', $return[0], "unreadable files are not errors");
    }

    /**
     * @param string $content
     * @return SplFileObject
     */
    private function getValidFileMock($content) {
        $file = $this->getMockBuilder('SplFileObject')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
             ->method('isFile')
            ->willReturn(true);
        $file->expects($this->any())
             ->method('getSize')
            ->willReturn(1);
        $file->expects($this->any())
             ->method('isReadable')
            ->willReturn(true);
        $file->expects($this->any())
             ->method('openFile')
            ->willReturnSelf();
        $file->expects($this->any())
             ->method('fread')
            ->willReturn($content);
        return $file;
    }

    /**
     * @return void
     */
    public function testCheckUnparsableJson()
    {
        $file = $this->getValidFileMock("broken");
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(1, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\ErrorMessage', $return[0], "broken files are not considered errors");
    }

    /**
     * @return void
     */
    public function testCheckNoObjectJson()
    {
        $file = $this->getValidFileMock("[\"\"]");
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(2, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\NoticeMessage', $return[0], "Having a parseable file is not a notice");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\NoticeMessage', $return[1], "Lack of schema is not a notice");
    }

    /**
     * @return void
     */
    public function testCheckNoSchemaJson()
    {
        $file = $this->getValidFileMock("{\"\":\"\"}");
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(2, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\NoticeMessage', $return[0], "Having a parseable file is not a notice");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\NoticeMessage', $return[1], "Lack of schema is not a notice");
    }

    /**
     * @return void
     */
    public function testCheckMissingSchemaJson()
    {
        $file = $this->getValidFileMock("{\"\$schema\":\"http://example.schema\"}");
        $store = $this->getMockBuilder('SchemaStore')
            ->getMock();
        $instance = new \De\Idrinth\JsonCheck\Service\FileValidator($store);
        $return = $instance->check($file);
        $this->assertCount(2, $return, "there were less messages returned than expected");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\NoticeMessage', $return[0], "Having a parseable file is not a notice");
        $this->assertInstanceOf('De\Idrinth\JsonCheck\Message\WarningMessage', $return[1], "Unknown schema is not a warning");
    }
}