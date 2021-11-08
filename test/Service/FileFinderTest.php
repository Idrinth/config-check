<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Service\FileFinder;
use PHPUnit\Framework\TestCase;

class FileFinderTest extends TestCase
{

    /**
     */
    public function testFind()
    {
        $instance = new FileFinder();
        $this->assertCount(8, $instance->find(__DIR__, 'php'));
        $this->assertCount(
            3,
            $instance->find(__DIR__, 'php', array('Validator'))
        );
        $this->assertCount(
            5,
            $instance->find(__DIR__, 'php', array('/File'))
        );
    }
}
