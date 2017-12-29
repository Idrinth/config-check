<?php

namespace De\Idrinth\ConfigCheck\Test\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore\Json;

class JsonTest extends BaseSchemaStoreTest
{
    /**
     * @test
     */
    public function testGetType()
    {
        $instance = $this->getInstance(array('.idrinth-cc.json' => $this->getSchemaPath()));
        $results = $instance->get('.idrinth-cc.json');
        $this->assertCount(1, $results);
        $this->assertInternalType('object', $results[0]);
    }

    /**
     * @param array $data
     * @return Json
     */
    protected function getInstance($data)
    {
        return new Json($this->getMockedFileRetriever(), $data);
    }
}