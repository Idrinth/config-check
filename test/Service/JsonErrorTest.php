<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\JsonError;
use PHPUnit\Framework\TestCase;


class JsonErrorTest extends TestCase
{
    /**
     */
    public function testGetLastError()
    {
        json_decode("[}");
        $this->assertNotEmpty(JsonError::getLastError(), "wrong syntax did not produce an error");
        json_decode("{}");
        $this->assertNotEmpty(JsonError::getLastError(), "correct syntax did not produce a message");
        json_decode("[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]", false, 5);
        $this->assertNotEmpty(JsonError::getLastError(), "deeply stacked arrays did not produce a message");
    }
}