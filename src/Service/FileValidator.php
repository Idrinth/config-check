<?php
namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use De\Idrinth\ConfigCheck\Message\WarningMessage;
use SplFileInfo;

abstract class FileValidator
{
    /**
     * @param SplFileInfo $file
     * @return Message[]
     */
    public function check(SplFileInfo $file)
    {
        $results = array();
        if(!$this->isFileUseable($results, $file)) {
            return $results;
        }
        return $this->validateContent(
            $results,
            $file->openFile()->fread($file->getSize())
        );
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    abstract protected function validateContent(array &$results, $content);

    /**
     * @param Message[] $results Reference!
     * @param SplFileInfo $file
     * @return boolean
     */
    private function isFileUseable(array &$results, SplFileInfo $file) {
        if(!$file->isFile() || $file->getSize() === 0) {
            $results[] = new WarningMessage("File is empty");
            return false;
        }
        if(!$file->isReadable()) {
            $results[] = new ErrorMessage("File is not readable");
            return false;
        }
        return true;
    }
}