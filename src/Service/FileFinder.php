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
    public function find(string $root, string $extension, array $blacklist = []): array
    {
        $result = [];
        $files = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root)
            ),
            '/^.+\.' . $extension . '$/i',
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
     * @return boolean
     */
    private function isBlacklisted(string $path, string $root, array $blacklist): bool
    {
        foreach ($blacklist as $forbidden) {
            $sysAdjusted = str_replace('/', DIRECTORY_SEPARATOR, $forbidden);
            $firstChar = substr($forbidden, 0, 1);
            if (
                ($firstChar === '/' && preg_match(
                    '/^' . preg_quote($root . $sysAdjusted, '/') . '/i',
                    $path
                )) || ($firstChar !== '/' && preg_match(
                    '/' . preg_quote($sysAdjusted, '/') . '/i',
                    $path
                ))
            ) {
                return true;
            }
        }
        return false;
    }
}
