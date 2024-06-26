<?php

namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore\Xml;

class XmlTestCase extends BaseSchemaStoreTestCase
{
    /**
     * @test
     */
    public function testGetType()
    {
        $instance = $this->getInstance(array('.idrinth-cc.json' => $this->getSchemaPath()));
        $results = $instance->get('.idrinth-cc.json');
        $this->assertCount(1, $results);
        $this->assertIsString($results[0]);
    }

    /**
     * @param array $data
     * @return Xml
     */
    protected function getInstance($data)
    {
        return new Xml($this->getMockedFileRetriever(), $data);
    }
}
