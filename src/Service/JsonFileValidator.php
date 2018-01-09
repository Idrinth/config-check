<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\NoticeMessage;

class JsonFileValidator extends FileValidator
{
    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    protected function validateContent(array &$results, $content)
    {
        $json = json_decode($content);
        if ($json === null) {
            $results[] = new ErrorMessage("File is not parseable: ".json_last_error_msg());
            return false;
        }
        if (!is_object($json) || !property_exists($json, '$schema')) {
            $results[] = new NoticeMessage("No schema provided");
            return false;
        }
        return true;
    }

    /**
     * @param type $filename
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateSchema($filename, array &$results, $content)
    {
        return $results;
    }
}
