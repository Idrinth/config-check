<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Data\SchemaStore;
use DOMDocument;
use LibXMLError;

class XmlFileValidator extends FileValidator
{
    public function __construct(SchemaStore $schemaStore)
    {
        parent::__construct($schemaStore);
        libxml_use_internal_errors(true);
    }

    protected function validateContent(string $content): bool
    {
        $isValid = true;
        libxml_clear_errors();
        if (false === simplexml_load_string($content)) {
            $this->error("XML not parseable by SimpleXML");
            $isValid = false;
            foreach (libxml_get_errors() as $error) {
                $this->error($this->getFromLibXML($error));
            }
        }
        libxml_clear_errors();
        $document = new DOMDocument();
        if (false === $document->loadXML($content)) {
            $this->error("XML not parseable by DomDocument");
            $isValid = false;
            foreach (libxml_get_errors() as $error) {
                $this->error($this->getFromLibXML($error));
            }
        }
        libxml_clear_errors();
        return $isValid;
    }

    private function getFromLibXML(LibXMLError $error): string
    {
        $levels = array(LIBXML_ERR_ERROR => 'Error',LIBXML_ERR_FATAL => 'Fatal',LIBXML_ERR_WARNING => 'Warning');
        return "[{$levels[$error->level]}] Line $error->line, Column $error->column: $error->message";
    }

    protected function validateSchema(string $filename, string $content): void
    {
        $document = new DOMDocument();
        $document->loadXML($content);
        if (!$document->doctype) {
            $this->notice("No DTD found.");
            return;
        }
        libxml_clear_errors();
        if (!$document->validate()) {
            $this->error("XML doesn't match DTD");
            foreach (libxml_get_errors() as $error) {
                $this->error($this->getFromLibXML($error));
            }
        }
        libxml_clear_errors();
    }
}
