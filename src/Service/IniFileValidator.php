<?php

namespace De\Idrinth\ConfigCheck\Service;

use De\Idrinth\ConfigCheck\Message;
use De\Idrinth\ConfigCheck\Message\ErrorMessage;

class IniFileValidator extends FileValidator
{

    /**
     * @param Message[] $results
     * @param string $content
     * @return Message[]
     */
    protected function validateContent(array &$results, $content)
    {
        $scanners = array(INI_SCANNER_NORMAL, INI_SCANNER_RAW);
        if (version_compare(PHP_VERSION, '5.6.1', '>=')) {
            $scanners[] = INI_SCANNER_TYPED;//not avaible before
        }
        foreach (array(true, false) as $sections) {
            foreach ($scanners as $scanner) {
                if (!parse_ini_string($content, $sections, $scanner)) {
                    $results[] = new ErrorMessage("Can't parse with settings: Scanner $scanner Sections $sections");
                }
            }
        }
        return $results;
    }
}
