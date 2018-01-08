<?php

namespace De\Idrinth\ConfigCheck\Test;

use De\Idrinth\ConfigCheck\Controller;
use De\Idrinth\ConfigCheck\Data\Config;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{

    /**
     */
    public function testGetCode()
    {
        $this->assertEquals(
            0,
            $this->getInstance($this->getNothingAllowedConfig())->getCode(),
            "There are errors without having checked..."
        );
        $this->assertEquals(
            1,
            $this->getInstance($this->getAllAllowedConfig())->getCode(),
            "There are no errors after having checked..."
        );
    }

    /**
     */
    public function testGetText()
    {
        $this->assertEquals(
            "\nConfig Check: OK\n0/0 OK\n\n\n",
            $this->getInstance($this->getNothingAllowedConfig())->getText(),
            "There are error-texts without having checked..."
        );
        $this->assertNotEmpty(
            $this->getInstance($this->getAllAllowedConfig())->getText(),
            "There are no error-texts after having checked..."
        );
    }

    /**
     * @param Config $configMock
     * @return Controller
     */
    private function getInstance($configMock)
    {
        return new Controller($configMock);
    }

    /**
     * @return Config
     */
    private function getNothingAllowedConfig()
    {
        return $this->getConfig(false);
    }

    /**
     * @return Config
     */
    private function getAllAllowedConfig()
    {
        return $this->getConfig(true);
    }

    /**
     * @return Config
     */
    private function getConfig($enabled)
    {
        $noneAllowed = $this->getMockBuilder('De\Idrinth\ConfigCheck\Data\Config')
            ->setConstructorArgs(array(__DIR__, $enabled ? array('w' => true) : array()))
            ->getMock();
        $noneAllowed->expects($this->any())
            ->method('isEnabled')
            ->willReturn($enabled);
        $noneAllowed->expects($this->any())
            ->method('getBlacklist')
            ->willReturn(array());
        $noneAllowed->expects($this->any())
            ->method('getRootDir')
            ->willReturn(__DIR__);
        return $noneAllowed;
    }
}
