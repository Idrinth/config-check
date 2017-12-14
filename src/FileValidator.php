<?php
namespace De\Idrinth\JsonCheck;

use SchemaStore;
use SplFileInfo;

class FileValidator
{
    private $file;
    private $schemas;
    public function __construct(SplFileInfo $file, SchemaStore $store)
    {
        $this->file = $file;
        $this->schemas = $store;
    }
    public function check()
    {
        if(!$this->file->isFile() || $this->file->getSize() === 0) {
            return [];
        }
        if(!$this->file->isReadable()) {
            return ["unreadable"];
        }
        $json = json_decode(file_get_contents($this->file->getPathname()));
        if($json === null) {
            return ["not parseable", json_last_error_msg()];
        }
        if(!isset($json['$schema'])) {
            return [];
        }
        if(!$this->schemas->get($json['$schema'])) {
            return ["unknown schema"];
        }
        $validator = new \Jsv4($json, $this->schemas->get($json['$schema']));
        return $validator->errors;
    }
}