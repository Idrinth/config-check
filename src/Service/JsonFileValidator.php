<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use JsonSchema\Validator;

class JsonFileValidator extends FileValidator
{
    public function __construct(SchemaStore $schemaStore, private Validator $validator)
    {
        parent::__construct($schemaStore);
    }

    protected function validateContent(string $content): bool
    {
        json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("File is not parseable: " . json_last_error_msg());
            return false;
        }
        return true;
    }

    protected function validateSchema(string $filename, string $content): void
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

    private function validateAll(mixed $json, array $schemata): void
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
