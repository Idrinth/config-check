<?php
namespace De\Irinth\ConfigCheck\Data\SchemaStore;

use De\Idrinth\ConfigCheck\Data\SchemaStore;

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
     * @param string[] $filemappings
     */
    function __construct($filemappings = array())
    {
        $this->filemappings = $filemappings;
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
        if ($uri) {
            $this->fill($list, $uri);
        }
        return $list;
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
            $this->schemata[$uri] = $this->prepareSchema(file_get_contents($uri));
        }
        $list[$uri] = $this->schemata[$uri];
    }

    /**
     * @param string the schema string
     */
    abstract protected function prepareSchema($schema);
}