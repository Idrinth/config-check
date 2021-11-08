<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\Yaml\Yaml;
use De\Idrinth\Yaml\YamlException;
use JsonSchema\Validator;

class YamlFileValidator extends FileValidator
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param SchemaStore $schemaStore
     * @param Validator $validator
     */
    public function __construct(SchemaStore $schemaStore, Validator $validator)
    {
        parent::__construct($schemaStore);
        $this->validator = $validator;
    }

    /**
     * @param string $content
     * @return boolean
     */
    protected function validateContent($content): bool
    {
        try {
            Yaml::decodeFromString($content);
            return true;
        } catch (YamlException $ex) {
            $this->error($ex->getMessage());
        }
        return false;
    }

    /**
     * @param string $filename
     * @param string $content
     * @return void
     */
    protected function validateSchema($filename, $content): void
    {
        $yaml = Yaml::decodeFromString($content);
        $schemata = $this->schemaStore->get($filename, null);
        if (!$schemata) {
            $this->notice("No schema provided");
            return;
        }
        $this->validateAll($yaml, $schemata);
    }

    /**
     * @param mixed $yaml
     * @param array $schemata
     * @return void
     */
    private function validateAll($yaml, array $schemata): void
    {
        foreach ($schemata as $schema) {
            $this->validator->reset();
            $this->validator->validate($yaml, $schema);
            foreach ($this->validator->getErrors() as $error) {
                $this->error(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
    }
}
