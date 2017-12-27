<?php

namespace De\Idrinth\ConfigCheck\Data;

class Config
{
    /**
     * @var array()
     */
    private $config;

    /**
     * @param string $rootDir
     * @param array $cliOptions
     */
    public function __construct($rootDir, $cliOptions)
    {
        $file = $rootDir.DIRECTORY_SEPARATOR.'.idrinth-cc.json';
        if (is_file($file)) {
            $this->config = json_decode(file_get_contents($file), true);
        }
        $this->config['warningsAsErrors'] = $this->getAdjustedWarningStatus($cliOptions);
        $this->config['verbosity'] = $this->getAdjustedVerbosity($cliOptions);
        $this->config['root'] = $rootDir;
    }

    /**
     * @param array $cliOptions
     * @return boolean
     */
    private function getAdjustedWarningStatus($cliOptions)
    {
        if (array_key_exists('w', $cliOptions)) {
            return true;
        }
        return isset($this->config['warningsAsErrors']) && $this->config['warningsAsErrors'];
    }

    /**
     * @param array $cliOptions
     * @return int
     */
    private function getAdjustedVerbosity($cliOptions)
    {
        if (array_key_exists('v', $cliOptions)) {
            return (int) $this->getParamCount($cliOptions, 'v');
        }
        return isset($this->config['verbosity']) ? (int) $this->config['verbosity'] : 0;
    }

    /**
     * @param array $params
     * @param string $key
     * @return int
     */
    private function getParamCount(array $params, $key)
    {
        if (!array_key_exists($key, $params)) {
            return 0;
        }
        return is_array($params[$key]) ? count($params[$key]) : 1;
    }

    /**
     * @param string $type
     * @return boolean
     */
    public function isEnabled($type)
    {
        return !isset($this->config[$type]) ||
            !isset($this->config[$type]['validity']) ||
            $this->config[$type]['validity'];
    }

    /**
     * @param string $type
     * @return string[]
     */
    public function getBlacklist($type)
    {
        $sources = array();
        if (isset($this->config['blacklist']) && is_array($this->config['blacklist'])) {
            $sources[] = $this->config['blacklist'];
        }
        if (isset($this->config[$type]['blacklist']) && is_array($this->config[$type]['blacklist'])) {
            $sources[] = $this->config[$type]['blacklist'];
        }
        switch (count($sources)) {
            case 2:
                return array_merge($sources[0], $sources[0]);
            case 1:
                return $sources[0];
            case 0:
            default:
                return array('/vendor');
        }
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->config['root'];
    }

    /**
     * @return int
     */
    public function getVerbosity()
    {
        return $this->config['verbosity'];
    }

    /**
     * @return boolean
     */
    public function hasWarningsAsErrors()
    {
        return $this->config['warningsAsErrors'];
    }
}
