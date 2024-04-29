<?php

namespace De\Idrinth\ConfigCheck\Data;

interface SchemaStore
{
    public function get(string $filename, ?string $uri = null): array;
}
