<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;

class IniFileValidator extends FileValidator
{

    /**
     * @param Message[] $results
     * @param string $content
     * @return boolean
     */
    protected function validateContent(array &$results, $content)
    {
        $isValid = true;
        $scanners = [INI_SCANNER_NORMAL, INI_SCANNER_RAW, INI_SCANNER_TYPED];
        foreach ([true, false] as $sections) {
            foreach ($scanners as $scanner) {
                $isValid = $this->checkContent($content, $sections, $scanner, $results) && $isValid;
            }
        }
        return $isValid;
    }

    /**
     * @param string $content
     * @param bool $sections
     * @param int $scanner
     * @param Message[] $results
     * @return bool
     */
    private function checkContent($content, bool $sections, int $scanner, array &$results): bool
    {
        if (@parse_ini_string($content, $sections, $scanner)) {
            return true;
        }
        $results[] = new ErrorMessage(
            $this->getFromInternalError(
                $scanner,
                $sections,
                error_get_last()
            )
        );
        return false;
    }

    /**
     * @param int $scanner
     * @param boolean $sections
     * @param array $error ["type", "message", "file", "line"]
     * @return string
     */
    private function getFromInternalError($scanner, $sections, array $error)
    {
        $sections = $sections ? 'yes' : 'no';
        $scanners = array('Normal','Raw','Typed');
        return "Can't parse with Scanner[{$scanners[$scanner]}] Sections[$sections]: {$error['message']}";
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
