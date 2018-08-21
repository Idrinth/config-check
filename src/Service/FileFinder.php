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
            if ($this->matchesPath($forbidden, $root, $path)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $forbidden
     * @param string $root
     * @param string $path
     * @return boolean
     */
    private function matchesPath($forbidden, $root, $path)
    {
        $sysAdjusted = str_replace('/', DIRECTORY_SEPARATOR, $forbidden);
        if ($forbidden{0} === '/') {
            return $this->isMatch($path, $root.$sysAdjusted);
        }
        return $this->isMatch($path, $sysAdjusted);
    }

    /**
     * @param string $path
     * @param string $toMath
     * @return boolean
     */
    private function isMatch($path, $toMath)
    {
        $match = preg_match('/^'.preg_quote($toMath, '/').'/i', $path);
        return is_int($match) && $match > 0;
    }
}
