<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\Yaml\Yaml;
use Exception;

class YamlFileValidator extends FileValidator
{

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateContent(array &$results, $content)
    {
        try {
            if (!Yaml::decodeFromString($content)) {
                $results[] = new ErrorMessage("Unable to parse the file's content");
            }
        } catch (Exception $ex) {
            $results[] = new ErrorMessage($ex->getMessage());
        }
        return $results;
    }
}
