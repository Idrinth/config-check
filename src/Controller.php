<?php

namespace De\Idrinth\ConfigCheck;

use De\Idrinth\ConfigCheck\Data\Config;
use De\Idrinth\ConfigCheck\Data\ValidationList;
use De\Idrinth\ConfigCheck\Service\FileFinder;
use De\Idrinth\ConfigCheck\Service\IniFileValidator;
use De\Idrinth\ConfigCheck\Service\JsonFileValidator;
use De\Idrinth\ConfigCheck\Service\XmlFileValidator;
use De\Idrinth\ConfigCheck\Service\YamlFileValidator;

class Controller
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $dir
     * @param array $params
     */
    public function __construct($dir, $params, Config $config)
    {
        $validators = array(
            'yml' => new YamlFileValidator(),
            'ini' => new IniFileValidator(),
            'xml' => new XmlFileValidator(),
            'json' => new JsonFileValidator(),
        );
        $validator = new ValidateFileList(new FileFinder(), $dir, $validators);
        $data = new ValidationList();
        foreach (array_keys($validators) as $type) {
            if ($config->isEnabled($type)) {
                $validator->process($type, $data, $config->getBlacklist($type));
            }
        }
        list($this->code, $this->text) = $data->finish(
            $this->getParamCount($params, 'v'),
            $this->getParamCount($params, 'w') > 0
        );
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
     * @return int
     */
    public function getCode()
    {
        return (int) $this->code;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
