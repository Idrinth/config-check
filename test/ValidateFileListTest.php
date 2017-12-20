<?php

namespace De\Idrinth\ConfigCheck\Test;

use De\Idrinth\ConfigCheck\ValidateFileList;
use PHPUnit\Framework\TestCase;

class ValidateFileListTest extends TestCase
{
    /**
     */
    public function testProcess()
    {
        $finder = $this->getMockBuilder('De\Idrinth\ConfigCheck\Service\FileFinder')
            ->getMock();
        $finder->expects($this->any())
            ->method('find')
            ->willReturn(array('b.j'));
        $validator = $this->getMockBuilder('De\Idrinth\ConfigCheck\Service\FileValidator')
            ->getMockForAbstractClass();
        $validator->expects($this->any())
            ->method('check')
            ->willReturn(array());
        $instance = new ValidateFileList($finder, __DIR__, array('j' => $validator));
        $list1 = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\ValidationList')
            ->getMock();
        $list1->expects($this->never())
            ->method('addFile')
            ->willReturn(null);
        $instance->process('qq', $list1);
        $list2 = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\ValidationList')
            ->getMock();
        $list2->expects($this->once())
            ->method('addFile')
            ->willReturn(null);
        $instance->process('j', $list2);
    }
}