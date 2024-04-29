<?php

namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore\Json;

class JsonTestCase extends BaseSchemaStoreTestCase
{
    /**
     * @test
     */
    public function testGetType()
    {
        $instance = $this->getInstance(array('.idrinth-cc.json' => $this->getSchemaPath()));
        $results = $instance->get('.idrinth-cc.json');
        $this->assertCount(1, $results);
        $this->assertIsObject($results[0]);
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
