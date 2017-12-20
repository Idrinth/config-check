<?php
namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;
use stdClass;

class JsonFileValidator extends FileValidator
{
    /**
     * @param Message[] $results Reference!
     * @param stdClass $json
     * @return boolean
     */
    private function isContentSchemaJson(array &$results, $json = null) {
        if($json === null) {
            $results[] = new ErrorMessage("File is not parseable: ".json_last_error_msg());
            return false;
        }
        if(!is_object($json) || !property_exists($json, '$schema')) {
            $results[] = new NoticeMessage("No schema provided");
            return false;
        }
        return true;
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateContent(array &$results, $content)
    {
        $json = json_decode($content);
        if(!$this->isContentSchemaJson($results, $json)) {
            return $results;
        }
        return $results;
    }
}