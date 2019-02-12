<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\Yaml\Yaml;
use De\Idrinth\Yaml\YamlException;

class YamlFileValidator extends FileValidator
{
    /**
     * @param string $content
     * @return boolean
     */
    protected function validateContent($content): bool
    {
        try {
            Yaml::decodeFromString($content);
            return true;
        } catch (YamlException $ex) {
            $this->error($ex->getMessage());
        }
        return false;
    }

    /**
     * @param string $filename
     * @param string $content
     * @return void
     */
    protected function validateSchema($filename, $content): void
    {
    }
}
