<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\FileValidator;
use PHPUnit\Framework\TestCase;

abstract class FileValidatorTest extends TestCase
{

    /**
     * @return FileValidator
     */
    abstract protected function getInstance();

    /**
     * @return void
     */
    public function testCheckNotFile()
    {
        $file = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\File')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
            ->method('isFile')
            ->willReturn(false);
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\WarningMessage',
            $return[0],
            "empty files are not warnings"
        );
    }

    /**
     * @return void
     */
    public function testCheckEmptyFile()
    {
        $file = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\File')
            ->setConstructorArgs(array(__FILE__))
            ->getMock();
        $file->expects($this->any())
            ->method('isFile')
            ->willReturn(true);
        $file->expects($this->any())
            ->method('getSize')
            ->willReturn(0);
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\WarningMessage',
            $return[0],
            "empty files are not warnings"
        );
    }

    /**
     * @return void
     */
    public function testCheckUnreadableFile()
    {
        $file = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\File')
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
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[0],
            "unreadable files are not errors"
        );
    }

    /**
     * @param string $content
     * @return File
     */
    protected function getValidFileMock($content)
    {
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
}
