<?php

namespace De\Idrinth\ConfigCheck\Service;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class FileFinder
{
    /**
     * @param string $root
     * @param string $extension
     * @param string[] $blacklist
     * @return string[]
     */
    public function find($root, $extension, $blacklist = array())
    {
        $result = array();
        $files = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root)
            ),
            '/^.+\.'.$extension.'$/i',
            RecursiveRegexIterator::GET_MATCH
        );
        foreach ($files as $path) {
            $path = $path[0];
            if (!$this->isBlacklisted($path, $root, $blacklist)) {
                $result[] = $path;
            }
        }
        return $result;
    }

    /**
     * @param string $path
     * @param string $root
     * @param string[] $blacklist
     * @return true
     */
    private function isBlacklisted($path, $root, $blacklist)
    {
        foreach ($blacklist as $forbidden) {
            $sysAdjusted = str_replace('/', DIRECTORY_SEPARATOR, $forbidden);
            if (($forbidden{0} === '/' && preg_match(
                '/^'.preg_quote($root.$sysAdjusted, '/').'/i',
                $path
            )) || ($forbidden{0} !== '/' && preg_match(
                '/'.preg_quote($sysAdjusted, '/').'/i',
                $path
            ))
            ) {
                return true;
            }
        }
        return false;
    }
}
