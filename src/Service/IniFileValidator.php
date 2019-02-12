<?php

namespace De\Idrinth\ConfigCheck\Service;

class IniFileValidator extends FileValidator
{
    /**
     * @param string $content
     * @return boolean
     */
    protected function validateContent($content): bool
    {
        $isValid = true;
        $scanners = [INI_SCANNER_NORMAL, INI_SCANNER_RAW, INI_SCANNER_TYPED];
        foreach ([true, false] as $sections) {
            foreach ($scanners as $scanner) {
                $isValid = $this->checkContent($content, $sections, $scanner) && $isValid;
            }
        }
        return $isValid;
    }

    /**
     * @param string $content
     * @param bool $sections
     * @param int $scanner
     * @return bool
     */
    private function checkContent($content, bool $sections, int $scanner): bool
    {
        if (@parse_ini_string($content, $sections, $scanner)) {
            return true;
        }
        $this->fromInternalError($scanner, $sections, error_get_last());
        return false;
    }

    /**
     * @param int $scanner
     * @param boolean $sections
     * @param array $error ["type", "message", "file", "line"]
     * @return void
     */
    private function fromInternalError($scanner, $sections, array $error): void
    {
        $sections = $sections ? 'yes' : 'no';
        $scanners = array('Normal','Raw','Typed');
        $this->error("Can't parse with Scanner[{$scanners[$scanner]}] Sections[$sections]: {$error['message']}");
    }

    /**
     * @param type $filename
     * @param string $content
     * @return void
     */
    protected function validateSchema($filename, $content): void
    {
    }
}
