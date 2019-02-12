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
        $file = $rootDir . DIRECTORY_SEPARATOR . '.idrinth-cc.json';
        $this->config = is_file($file) ? json_decode(file_get_contents($file), true) : array();
        $this->config['warningsAsErrors'] = $this->processWarningStatus($cliOptions);
        $this->config['verbosity'] = $this->processVerbosity($cliOptions);
        $this->config['root'] = $rootDir;
    }

    /**
     * @param array $cliOptions
     * @return boolean
     */
    private function processWarningStatus($cliOptions)
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
    private function processVerbosity($cliOptions)
    {
        return max(array(
            isset($this->config['verbosity']) ? (int) $this->config['verbosity'] : 0,
            $this->getParamCount($cliOptions, 'v')
        ));
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
                return array_merge($sources[0], $sources[1]);
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

    /**
     * @return string[]
     */
    public function getMapping($type)
    {
        return isset($this->config[$type]) && isset($this->config[$type]['mapping']) ?
            $this->config[$type]['mapping'] :
            array();
    }

    /**
     * @return string[]
     */
    public function getExtensions($type)
    {
        if (isset($this->config[$type]) && isset($this->config[$type]['additional-extensions'])) {
            return array_merge(array($type), $this->config[$type]['additional-extensions']);
        }
        return array($type);
    }
}
