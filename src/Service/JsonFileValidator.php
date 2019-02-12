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
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    protected function validateContent(array &$results, $content)
    {
        $json = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $results[] = new ErrorMessage("File is not parseable: " . json_last_error_msg());
            return false;
        }
        return true;
    }

    /**
     * @param string $filename
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateSchema($filename, array &$results, $content)
    {
        $json = json_decode($content);
        $hasSchema = is_object($json) && property_exists($json, '$schema');
        $schemata = $this->schemaStore->get($filename, $hasSchema ? $json->{'$schema'} : null);
        if (!$schemata) {
            if (!$hasSchema) {
                $results[] = new NoticeMessage("No schema provided");
            }
            return $results;
        }
        return $this->validateAll($json, $schemata, $results);
    }

    /**
     * @param mixed $json
     * @param array $schemata
     * @param Message[] $results
     * @return Message[]
     */
    private function validateAll($json, array $schemata, array $results): array
    {
        foreach ($schemata as $schema) {
            $this->validator->reset();
            $this->validator->validate($json, $schema);
            foreach ($this->validator->getErrors() as $error) {
                $results[] = new ErrorMessage(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
        return $results;
    }
}
