<?php
namespace De\Idrinth\JsonCheck\Service;

use De\Idrinth\JsonCheck\Message\ErrorMessage;
use De\Idrinth\JsonCheck\Message\NoticeMessage;
use De\Idrinth\JsonCheck\Message\WarningMessage;
use Jsv4;
use SchemaStore;
use SplFileInfo;

class FileValidator
{
    /**
     * @var SchemaStore
     */
    private $schemas;

    /**
     * @param SchemaStore $store
     */
    public function __construct(SchemaStore $store)
    {
        $this->schemas = $store;
    }

    /**
     * @param SplFileInfo $file
     * @return Message[]
     */
    public function check(SplFileInfo $file)
    {
        $results = array();
        if(!$file->isFile() || $file->getSize() === 0) {
            $results[] = new WarningMessage("File is empty");
            return $results;
        }
        if(!$file->isReadable()) {
            $results[] = new ErrorMessage("File is not readable");
            return $results;
        }
        $json = json_decode(file_get_contents($file->getPathname()));
        if($json === null) {
            $results[] = new ErrorMessage("File is not parseable: ".json_last_error_msg());
            return $results;
        } else {
            $results[] = new NoticeMessage("File is parseable");
        }
        if(!is_object($json) || !property_exists($json, '$schema')) {
            $results[] = new NoticeMessage("No schema provided");
            return $results;
        }
        if(!$this->schemas->get($json->{'$schema'})) {
            $results[] = new WarningMessage("Schema not avaible");
            return $results;
        }
        $validator = new Jsv4($json, $this->schemas->get($json->{'$schema'}));
        if(!count($validator->errors)) {
            $results[] = new NoticeMessage("Content matched schema");
            return $results;
        }
        foreach($validator->errors as $error) {
            $results[] = new ErrorMessage($error->message."($error->code $error->schemaPath cmp. $error->dataPath)");
        }
        return $results;
    }
}