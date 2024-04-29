<?php

namespace De\Idrinth\ConfigCheck\Data;

use RuntimeException;
use SplFileInfo;

class File
{
    private SplFileInfo $file;

    public function __construct(string $path)
    {
        $this->file = new SplFileInfo($path);
    }

    public function isFile(): bool
    {
        return $this->file->isFile();
    }

    public function isReadable(): bool
    {
        return $this->file->isReadable();
    }

    public function getSize(): int
    {
        try {
            return $this->file->getSize();
        } catch (RuntimeException $exception) {
            return 0;
        }
    }

    public function getContent(): string
    {
        try {
            return file_get_contents($this->file->getPathname()) ?: '';
        } catch (RuntimeException $exception) {
            return '';
        }
    }

    public function getName(): string
    {
        return $this->file->getFilename();
    }
}
