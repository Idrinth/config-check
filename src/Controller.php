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
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $validators = array(
            'yml' => new YamlFileValidator(),
            'ini' => new IniFileValidator(),
            'xml' => new XmlFileValidator(),
            'json' => new JsonFileValidator(),
        );
        $validator = new ValidateFileList(new FileFinder(), $config->getRootDir(), $validators);
        $data = new ValidationList();
        foreach (array_keys($validators) as $type) {
            if ($config->isEnabled($type)) {
                $validator->process($type, $data, $config->getBlacklist($type));
            }
        }
        list($this->code, $this->text) = $data->finish(
            $config->getVerbosity(),
            $config->hasWarningsAsErrors()
        );
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
