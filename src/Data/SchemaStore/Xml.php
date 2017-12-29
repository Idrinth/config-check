<?php
namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

class Xml extends BaseSchemaStore
{
    /**
     * @param string $schema
     * @return string
     */
    protected function prepareSchema($schema)
    {
        return $schema;
    }
}