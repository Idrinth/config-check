<?php

namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Service\FileRetriever;

abstract class BaseSchemaStore implements SchemaStore
{
    /**
     * @var array<string, string> [uri => data]
     */
    private array $schemata = [];

    /**
     * @param FileRetriever $retriever
     * @param array<string, string> $filemappings
     */
    public function __construct(private FileRetriever $retriever, private $filemappings = [])
    {
    }

    public function get(string $filename, ?string $uri = null): array
    {
        $list = [];
        if (isset($this->filemappings[$filename])) {
            $this->fill($list, $this->filemappings[$filename]);
        }
        if ($uri) {
            $this->fill($list, $uri);
        }
        return array_values($list);
    }

    protected function fill(array &$list, string $uri): void
    {
        if (isset($list[$uri])) {
            return;
        }
        if (!isset($this->schemata[$uri])) {
            $this->schemata[$uri] = $this->prepareSchema($this->retriever->get($uri));
        }
        $list[$uri] = $this->schemata[$uri];
    }

    abstract protected function prepareSchema(string $schema): mixed;
}
