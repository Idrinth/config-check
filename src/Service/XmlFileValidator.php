<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;

class XmlFileValidator extends FileValidator
{

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateContent(array &$results, $content)
    {
        if (!simplexml_load_string($content)) {
            $results[] = new ErrorMessage("Can't parse content as xml");
        }
        return $results;
    }
}
