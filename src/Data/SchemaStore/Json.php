<?php
namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

class Json extends BaseSchemaStore
{
    /**
     * @param string $schema
     * @return object
     */
    protected function prepareSchema($schema)
    {
        return json_decode($schema);
    }
}
