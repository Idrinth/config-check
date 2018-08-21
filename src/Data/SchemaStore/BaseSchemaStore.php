<?php
namespace De\Idrinth\ConfigCheck\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Service\FileRetriever;

abstract class BaseSchemaStore implements SchemaStore
{
    /**
     * uri => data
     * @var array
     */
    private $schemata = array();

    /**
     * @var string[]
     */
    private $filemappings;

    /**
     * @var FileRetriever
     */
    private $retriever;

    /**
     * @param FileRetriever $retriever
     * @param string[] $filemappings
     */
    public function __construct(FileRetriever $retriever, $filemappings = array())
    {
        $this->filemappings = $filemappings;
        $this->retriever = $retriever;
    }

    /**
     * @param type $filename
     * @param type $uri
     * @return array
     */
    public function get($filename, $uri = null)
    {
        $list = array();
        if (isset($this->filemappings[$filename])) {
            $this->fill($list, $this->filemappings[$filename]);
        }
        if (is_string($uri) && strlen($uri) > 0) {
            $this->fill($list, $uri);
        }
        return array_values($list);
    }

    /**
     * @param string $uri
     */
    protected function fill(&$list, $uri)
    {
        if (isset($list[$uri])) {
            return;
        }
        if (!isset($this->schemata[$uri])) {
            $this->schemata[$uri] = $this->prepareSchema($this->retriever->get($uri));
        }
        $list[$uri] = $this->schemata[$uri];
    }

    /**
     * @param string the schema string
     */
    abstract protected function prepareSchema($schema);
}
