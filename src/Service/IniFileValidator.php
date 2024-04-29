<?php

namespace De\Idrinth\ConfigCheck\Service;

class IniFileValidator extends FileValidator
{
    protected function validateContent(string $content): bool
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

    private function checkContent(string $content, bool $sections, int $scanner): bool
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
     * @param array<int, string|int> $error ["type", "message", "file", "line"]
     * @return void
     */
    private function fromInternalError(int $scanner, bool $sections, array $error): void
    {
        $sections = $sections ? 'yes' : 'no';
        $scanners = array('Normal','Raw','Typed');
        $this->error("Can't parse with Scanner[{$scanners[$scanner]}] Sections[$sections]: {$error['message']}");
    }

    protected function validateSchema(string $filename, string $content): void
    {
    }
}
