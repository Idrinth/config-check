<?php

namespace De\Idrinth\ConfigCheck\Data;

class Config
{
    private array $config;

    public function __construct(string $rootDir, array $cliOptions)
    {
        $file = $rootDir . DIRECTORY_SEPARATOR . '.idrinth-cc.json';
        $this->config = is_file($file) ? json_decode(file_get_contents($file), true) : [];
        $this->config['warningsAsErrors'] = $this->processWarningStatus($cliOptions);
        $this->config['verbosity'] = $this->processVerbosity($cliOptions);
        $this->config['root'] = $rootDir;
    }

    private function processWarningStatus(array $cliOptions): bool
    {
        if (array_key_exists('w', $cliOptions)) {
            return true;
        }
        return isset($this->config['warningsAsErrors']) && $this->config['warningsAsErrors'];
    }

    private function processVerbosity(array $cliOptions): int
    {
        return max(array(
            isset($this->config['verbosity']) ? (int) $this->config['verbosity'] : 0,
            $this->getParamCount($cliOptions, 'v')
        ));
    }

    private function getParamCount(array $params, string $key): string
    {
        if (! isset($params[$key]) && ! array_key_exists($key, $params)) {
            return 0;
        }
        return is_array($params[$key]) ? count($params[$key]) : 1;
    }

    public function isEnabled(string $type): bool
    {
        return !isset($this->config[$type]) ||
            !isset($this->config[$type]['validity']) ||
            $this->config[$type]['validity'];
    }

    /**
     * @param string $type
     * @return string[]
     */
    public function getBlacklist(string $type): array
    {
        $sources = [];
        if (isset($this->config['blacklist']) && is_array($this->config['blacklist'])) {
            $sources[] = $this->config['blacklist'];
        }
        if (isset($this->config[$type]['blacklist']) && is_array($this->config[$type]['blacklist'])) {
            $sources[] = $this->config[$type]['blacklist'];
        }
        return match (count($sources)) {
            2 => array_merge($sources[0], $sources[1]),
            1 => $sources[0],
            default => array('/vendor'),
        };
    }

    public function getRootDir(): string
    {
        return $this->config['root'];
    }

    public function getVerbosity(): int
    {
        return $this->config['verbosity'];
    }

    public function hasWarningsAsErrors(): bool
    {
        return $this->config['warningsAsErrors'];
    }

    public function getMapping(string $type): array
    {
        return $this->config[$type]['mapping'] ?? [];
    }

    /**
     * @return string[]
     */
    public function getExtensions($type): array
    {
        return array_merge(array($type), $this->config[$type]['additional-extensions'] ?? []);
    }
}
