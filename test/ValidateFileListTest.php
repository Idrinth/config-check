<?php

namespace De\Idrinth\ConfigCheck\Test;

use De\Idrinth\ConfigCheck\ValidateFileList;
use PHPUnit\Framework\TestCase;

class ValidateFileListTest extends TestCase
{
    /**
     * @return FileValidator
     */
    private function getValidatorMock() {
        $validator = $this->getMockBuilder('De\Idrinth\ConfigCheck\Service\FileValidator')
            ->getMock();
        $validator->expects($this->any())
            ->method('check')
            ->willReturn(array($this->getMessage()));
        return $validator;
    }

    /**
     * @return Message
     */
    private function getMessage() {
        return $this->getMockBuilder('De\Idrinth\ConfigCheck\Message')
            ->getMock();
    }

    /**
     * @return FileFinder
     */
    private function getFinderMock() {
        $finder = $this->getMockBuilder('De\Idrinth\ConfigCheck\Service\FileFinder')
            ->getMock();
        $finder->expects($this->any())
            ->method('find')
            ->willReturn(array(__FILE__));
        return $finder;
    }
    /**
     * @param boolean $isCalled
     * @return ValidationList
     */
    private function getListMock($isCalled) {
        $list = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\ValidationList')
            ->getMock();
        $list->expects($isCalled ? $this->once() : $this->never())
            ->method('addFile')
            ->willReturn(null);
        return $list;
    }

    /**
     */
    public function testProcess()
    {
        $instance = new ValidateFileList($this->getFinderMock(), __DIR__, array('php' => $this->getValidatorMock()));
        $instance->process('qq', $this->getListMock(false));
        $instance->process('php', $this->getListMock(true));
    }
}