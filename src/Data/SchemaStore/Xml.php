<?php

namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

class Xml extends BaseSchemaStore
{
    protected function prepareSchema(string $schema): string
    {
        return $schema;
    }
}
