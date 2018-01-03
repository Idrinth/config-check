<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;

class XmlFileValidator extends FileValidator
{
    public function __construct()
    {
        libxml_use_internal_errors(true);
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateContent(array &$results, $content)
    {
        if (!@simplexml_load_string($content)) {
            $results[] = new ErrorMessage("XML not parseable");
            foreach (libxml_get_errors() as $error) {
                $results[] = new ErrorMessage($error->message);
            }
        }
        return $results;
    }
}
