<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use JsonSchema\Validator;

class JsonFileValidator extends FileValidator
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
        $json = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("File is not parseable: " . json_last_error_msg());
            return false;
        }
        return true;
    }

    /**
     * @param string $filename
     * @param string $content
     * @return void
     */
    protected function validateSchema($filename, $content): void
    {
        $json = json_decode($content);
        $hasSchema = is_object($json) && property_exists($json, '$schema');
        $schemata = $this->schemaStore->get($filename, $hasSchema ? $json->{'$schema'} : null);
        if (!$schemata) {
            if (!$hasSchema) {
                $this->notice("No schema provided");
            }
            return;
        }
        $this->validateAll($json, $schemata);
    }

    /**
     * @param mixed $json
     * @param array $schemata
     * @return void
     */
    private function validateAll($json, array $schemata): void
    {
        foreach ($schemata as $schema) {
            $this->validator->reset();
            $this->validator->validate($json, $schema);
            foreach ($this->validator->getErrors() as $error) {
                $this->error(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
    }
}
