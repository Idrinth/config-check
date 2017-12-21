<?php

namespace De\Idrinth\ConfigCheck\Test;

use De\Idrinth\ConfigCheck\ValidateFileList;
use PHPUnit\Framework\TestCase;

class ValidateFileListTest extends TestCase
{
    /**
     * @param boolean $isCalled
     * @return FileValidator
     */
    private function getValidatorMock($isCalled)
    {
        $validator = $this->getMockBuilder('De\Idrinth\ConfigCheck\Service\FileValidator')
            ->getMock();
        $validator->expects($isCalled ? $this->once() : $this->never())
            ->method('check')
            ->willReturn(array());
        return $validator;
    }

    /**
     * @return FileFinder
     */
    private function getFinderMock()
    {
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
    private function getListMock($isCalled)
    {
        $list = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\ValidationList')
            ->getMock();
        $list->expects($isCalled ? $this->once() : $this->never())
            ->method('addFile');
        return $list;
    }

    /**
     */
    public function testProcessSuccess()
    {
        $this->runProcess('php');
    }

    /**
     */
    public function testProcessFailure()
    {
        $this->runProcess('qq');
    }

    /**
     * @param string $ext
     */
    private function runProcess($ext)
    {
        $exists = $ext==='php';
        $instance = new ValidateFileList(
            $this->getFinderMock(),
            __DIR__,
            array('php' => $this->getValidatorMock($exists))
        );
        $list = $this->getListMock($exists);
        $instance->process($ext, $list);
    }
}