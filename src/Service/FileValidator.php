<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\File;
use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use De\Idrinth\ConfigCheck\Message\WarningMessage;
use Exception;

abstract class FileValidator
{
    /**
     * @var Message[]
     */
    private array $results = [];

    public function __construct(protected SchemaStore $schemaStore)
    {
    }

    /**
     * @param File $file
     * @return Message[]
     */
    public function check(File $file): array
    {
        $this->results = [];
        try {
            if ($this->isFileUsable($file) && $this->validateContent($file->getContent())) {
                $this->validateSchema($file->getName(), $file->getContent());
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        return $this->results;
    }

    protected function error(string $message): void
    {
        $this->results[] = new ErrorMessage($message);
    }

    protected function warning(string $message): void
    {
        $this->results[] = new WarningMessage($message);
    }

    protected function notice(string $message): void
    {
        $this->results[] = new NoticeMessage($message);
    }

    abstract protected function validateContent(string $content): bool;
    abstract protected function validateSchema(string $filename, string $content): void;

    private function isFileUsable(File $file): bool
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
