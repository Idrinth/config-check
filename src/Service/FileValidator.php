<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\File;
use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use De\Idrinth\ConfigCheck\Message\WarningMessage;
use Exception;

abstract class FileValidator
{
    /**
     * @var Message[]
     */
    private $results = [];

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
        $this->results = [];
        try {
            if ($this->isFileUseable($file) && $this->validateContent($file->getContent())) {
                $this->validateSchema($file->getName(), $file->getContent());
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        return $this->results;
    }

    /**
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->results[] = new ErrorMessage($message);
    }

    /**
     * @param string $message
     * @return void
     */
    protected function warning(string $message): void
    {
        $this->results[] = new WarningMessage($message);
    }

    /**
     * @param string $message
     * @return void
     */
    protected function notice(string $message): void
    {
        $this->results[] = new NoticeMessage($message);
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    abstract protected function validateContent($content): bool;

    /**
     * @param string $content
     * @return void
     */
    abstract protected function validateSchema($filename, $content): void;

    /**
     * @param File $file
     * @return boolean
     */
    private function isFileUseable(File $file)
    {
        if (!$file->isFile() || $file->getSize() === 0) {
            $this->warning("File is empty");
            return false;
        }
        if (!$file->isReadable()) {
            $this->error("File is not readable");
            return false;
        }
        return true;
    }
}
