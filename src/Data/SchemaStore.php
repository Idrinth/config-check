<?php
namespace De\Idrinth\ConfigCheck\Data;

interface SchemaStore
{
    /**
     * @param string $filename
     * @param string $uri
     * @return array matching schemata
     */
    public function get($filename, $uri = null);
}
