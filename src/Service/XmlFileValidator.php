<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;
use De\Idrinth\ConfigCheck\Message\WarningMessage;
use DOMDocument;
use LibXMLError;

class XmlFileValidator extends FileValidator
{
    /**
     * @param SchemaStore $schemaStore
     */
    public function __construct(SchemaStore $schemaStore)
    {
        parent::__construct($schemaStore);
        libxml_use_internal_errors(true);
    }

    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    protected function validateContent(array &$results, $content)
    {
        $isValid = true;
        libxml_clear_errors();
        if (false === simplexml_load_string($content)) {
            $results[] = new WarningMessage("XML not parseable by SimpleXML");
            $isValid = false;
            foreach (libxml_get_errors() as $error) {
                $results[] = new ErrorMessage($this->getFromLibXML($error));
            }
        }
        libxml_clear_errors();
        $document = new DOMDocument();
        if (false === $document->loadXML($content)) {
            $results[] = new WarningMessage("XML not parseable by DomDocument");
            $isValid = false;
            foreach (libxml_get_errors() as $error) {
                $results[] = new ErrorMessage($this->getFromLibXML($error));
            }
        }
        libxml_clear_errors();
        return $isValid;
    }

    /**
     * @param LibXMLError $error
     * @return string
     */
    private function getFromLibXML(LibXMLError $error)
    {
        $levels = array(LIBXML_ERR_ERROR => 'Error',LIBXML_ERR_FATAL=>'Fatal',LIBXML_ERR_WARNING=>'Warning');
        return "[{$levels[$error->level]}] Line $error->line, Column $error->column: $error->message";
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
