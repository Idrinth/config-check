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
        if ($json === null) {
            $results[] = new ErrorMessage("File is not parseable: " . json_last_error_msg());
            return false;
        }
        if (!is_object($json) || !property_exists($json, '$schema')) {
            $results[] = new NoticeMessage("No schema provided");
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
        $schemata = $this->schemaStore->get($filename, property_exists($json, '$schema') ? $json->{'$schema'} : null);
        if (!$schemata) {
            return $results;
        }
        foreach ($schemata as $schema) {
            $this->validator->validate($json, $schema);
            foreach ($this->validator->getErrors() as $error) {
                $results[] = new ErrorMessage(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
        return $results;
    }
}
