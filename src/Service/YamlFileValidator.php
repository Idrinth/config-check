<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\Yaml\Yaml;
use De\Idrinth\Yaml\YamlException;

class YamlFileValidator extends FileValidator
{
    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    protected function validateContent(array &$results, $content)
    {
        try {
            Yaml::decodeFromString($content);
            return true;
        } catch (YamlException $ex) {
            $results[] = new ErrorMessage($ex->getMessage());
        }
        return false;
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
