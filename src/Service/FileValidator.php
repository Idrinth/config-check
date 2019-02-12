<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\File;
use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\WarningMessage;
use Exception;

abstract class FileValidator
{
    /**
     * @var SchemaStore
     */
    protected $schemaStore;

    /**
     * @param SchemaStore $schemaStore
     */
    public function __construct(SchemaStore $schemaStore)
    {
        $this->schemaStore = $schemaStore;
    }

    /**
     * @param File $file
     * @return Message[]
     */
    public function check(File $file)
    {
        $results = array();
        if (!$this->isFileUseable($results, $file) || !$this->validateContent($results, $file->getContent())) {
            return $results;
        }
        try {
            return $this->validateSchema(
                $file->getName(),
                $results,
                $file->getContent()
            );
        } catch (Exception $exception) {
            $results[] = new ErrorMessage($exception->getMessage());
            return $results;
        }
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    abstract protected function validateContent(array &$results, $content);

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    abstract protected function validateSchema($filename, array &$results, $content);

    /**
     * @param Message[] $results Reference!
     * @param File $file
     * @return boolean
     */
    private function isFileUseable(array &$results, File $file)
    {
        if (!$file->isFile() || $file->getSize() === 0) {
            $results[] = new WarningMessage("File is empty");
            return false;
        }
        if (!$file->isReadable()) {
            $results[] = new ErrorMessage("File is not readable");
            return false;
        }
        return true;
    }
}
