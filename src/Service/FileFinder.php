<?php
namespace De\Idrinth\JsonCheck\Service;

use De\Idrinth\JsonCheck\Data\JsonFileResult;
use De\Idrinth\JsonCheck\Data\ValidationList;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use SchemaStore;
use SplFileInfo;

class FileFinder
{
    /**
     * @var FileValidator
     */
    private $validator;

    /**
     * @param SchemaStore $schemas defaults to an empty one
     */
    public function __construct(SchemaStore $schemas = null)
    {
        $this->validator = new FileValidator($schemas ?: new SchemaStore());
    }

    /**
     * @param string $root
     * @return ValidationList
     */
    public function checkDir($root) {
        $result = new ValidationList();
        $files = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root)
            ),
            '/^.+\.json$/i',
            RecursiveRegexIterator::GET_MATCH
        );
        foreach($files as $path) {
            $path = $path[0];
            if(preg_match('/(\\\\|\\/|^)vendor(\\\\|\\/)/i', $path)) {
                continue;
            }
            $file = new JsonFileResult($path);
            $result->addFile($file);
            foreach($this->validator->check(new SplFileInfo($path)) as $message) {
                $file->addMessage($message);
            }
        }
        return $result;
    }
}