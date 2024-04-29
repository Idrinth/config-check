<?php

namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

class Json extends BaseSchemaStore
{
    protected function prepareSchema(string $schema): object
    {
        return json_decode($schema);
    }
}
