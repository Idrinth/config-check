<?php
namespace De\Idrinth\ConfigCheck\Data;

use RuntimeException;
use SplFileInfo;

class File
{
    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->file = new SplFileInfo($path);
    }

    /**
     * @return boolean
     */
    public function isFile() {
        return $this->file->isFile();
    }

    /**
     * @return boolean
     */
    public function isReadable() {
        return $this->file->isReadable();
    }

    /**
     * Prevents exceptions that are not useful here
     * @return int
     */
    public function getSize() {
        try {
            return $this->file->getSize();
        } catch(RuntimeException $exception) {
            return 0;
        }
    }

    /**
     * Prevents exceptions that are not useful here and returns the complete avaible content
     * @return string
     */
    public function getContent() {
        try {
            return file_get_contents($this->file->getPathname()) ?: '';
        } catch(RuntimeException $exception) {
            return '';
        }
    }
}