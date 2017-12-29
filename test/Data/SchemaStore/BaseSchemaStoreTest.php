<?php

namespace De\Idrinth\ConfigCheck\Test\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore\BaseSchemaStore;
use De\Idrinth\ConfigCheck\Service\FileRetriever;
use PHPUnit\Framework\TestCase;

abstract class BaseSchemaStoreTest extends TestCase
{
    /**
     * @return BaseSchemaStore
     */
    protected abstract function getInstance($data);

    /**
     * @test
     */
    public function testGet()
    {
        $schema = $this->getSchemaPath();
        $instance = $this->getInstance(array(
            'composer.json' => 'https://getcomposer.org/schema.json',
            '.idrinth-cc.json' => $schema
        ));
        $this->assertCount(1, $instance->get('.idrinth-cc.json'));
        $this->assertCount(1, $instance->get('composer.json'));
        $this->assertCount(0, $instance->get('composer.lock'));
        $this->assertCount(2, $instance->get('composer.json', $schema));
        $this->assertCount(1, $instance->get('.idrinth-cc.json', $schema));
    }

    /**
     * @return FileRetriever
     */
    protected function getMockedFileRetriever()
    {
        $mock = $this->getMockBuilder('\De\Idrinth\ConfigCheck\Service\FileRetriever')
            ->setConstructorArgs(array(''))
            ->getMock();
        $mock->expects($this->any())
            ->method('get')
            ->willReturn("{}");
        return $mock;
    }

    /**
     * @return string
     */
    protected function getSchemaPath()
    {
        return dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'schema.json';
    }
}