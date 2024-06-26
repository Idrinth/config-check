<?php

namespace De\Idrinth\ConfigCheck;

use De\Idrinth\ConfigCheck\Data\File;
use De\Idrinth\ConfigCheck\Data\FileResult;
use De\Idrinth\ConfigCheck\Data\ValidationList;
use De\Idrinth\ConfigCheck\Service\FileFinder;
use De\Idrinth\ConfigCheck\Service\FileValidator;

class ValidateFileList
{
    private FileFinder $finder;
    private string $baseDir;

    /**
     * @var FileValidator[]
     */
    private array $validators;

    /**
     * @param FileFinder $finder
     * @param string $baseDir
     * @param FileValidator[] $validators
     */
    public function __construct(
        FileFinder $finder,
        string $baseDir,
        array $validators = []
    ) {
        $this->finder = $finder;
        $this->baseDir = $baseDir;
        $this->validators = $validators;
    }

    /**
     * @param string $extension
     * @param string $type
     * @param ValidationList &$data
     * @param string[] $blacklist
     * @return ValidationList
     */
    public function process(
        string $extension,
        string $type,
        ValidationList &$data,
        array $blacklist = []
    ) {
        if (!isset($this->validators[$type])) {
            return $data;
        }
        foreach ($this->finder->find($this->baseDir, $extension, $blacklist) as $file) {
            $result = new FileResult(substr($file, strlen($this->baseDir) + 1));
            foreach ($this->validators[$type]->check(new File($file)) as $message) {
                $result->addMessage($message);
            }
            $data->addFile($result);
        }
        return $data;
    }
}
